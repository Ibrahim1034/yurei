<?php

namespace App\Http\Controllers;

use App\Models\MembershipCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Barryvdh\DomPDF\Facade\Pdf;

class MembershipCardController extends Controller
{
    /**
     * Show the form for creating a membership card.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Check if user already has a membership card
        if ($user->membershipCard) {
            return redirect()->route('membership-card.show')
                ->with('error', 'You already have a membership card.');
        }

        return view('membership-card.create');
    }

    /**
     * Store a newly created membership card.
     */
   public function store(Request $request)
    {
        $user = auth()->user();

        // Check if user already has a membership card
        if ($user->membershipCard) {
            return redirect()->route('membership-card.show')
                ->with('error', 'You already have a membership card.');
        }

        $request->validate([
            'card_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cropped_image' => 'required|string',
        ], [
            'card_photo.required' => 'Please upload a passport photo.',
            'card_photo.image' => 'The file must be an image.',
            'card_photo.mimes' => 'The image must be a JPEG, PNG, or JPG file.',
            'card_photo.max' => 'The image size must not exceed 2MB.',
            'cropped_image.required' => 'Please crop your image before submitting.',
        ]);

        try {
            // Process the cropped image
            if ($request->cropped_image) {
                $croppedImage = $request->cropped_image;
                
                // Remove the data:image/jpeg;base64, part
                $imageData = explode(',', $croppedImage);
                $imageData = base64_decode($imageData[1]);
                
                // Generate filename
                $filename = 'card_photos/' . uniqid() . '.jpg';
                
                // Save the cropped image directly
                Storage::disk('public')->put($filename, $imageData);
                
                // Verify the image was saved and create a square version using GD
                $imagePath = storage_path('app/public/' . $filename);
                
                // Use GD to ensure square dimensions
                if (extension_loaded('gd')) {
                    $sourceImage = imagecreatefromstring(file_get_contents($imagePath));
                    
                    if ($sourceImage !== false) {
                        $width = imagesx($sourceImage);
                        $height = imagesy($sourceImage);
                        
                        // Create square image (400x400)
                        $squareSize = 400;
                        $squareImage = imagecreatetruecolor($squareSize, $squareSize);
                        
                        // Fill with white background (optional)
                        $white = imagecolorallocate($squareImage, 255, 255, 255);
                        imagefill($squareImage, 0, 0, $white);
                        
                        // Calculate dimensions to fit the image in the square
                        $sourceSize = min($width, $height);
                        $sourceX = ($width - $sourceSize) / 2;
                        $sourceY = ($height - $sourceSize) / 2;
                        
                        // Resize and center the image in the square
                        imagecopyresampled(
                            $squareImage, $sourceImage,
                            0, 0, $sourceX, $sourceY,
                            $squareSize, $squareSize, $sourceSize, $sourceSize
                        );
                        
                        // Save the optimized image
                        imagejpeg($squareImage, $imagePath, 80);
                        
                        // Free memory
                        imagedestroy($sourceImage);
                        imagedestroy($squareImage);
                    }
                }
            }

            // Calculate dates
            $registrationDate = now();
            $expirationDate = now()->addYear(); // 1 year validity

            // Create membership card with user data
            $membershipCard = MembershipCard::create([
                'user_id' => $user->id,
                'membership_number' => MembershipCard::generateMembershipNumber($user->user_type),
                'card_photo_path' => $filename,
                'registration_date' => $registrationDate,
                'expiration_date' => $expirationDate,
                'user_type' => $user->user_type,
                'county' => $user->county,
                'constituency' => $user->constituency,
                'ward' => $user->ward,
                'institution' => $user->institution,
                'graduation_status' => $user->graduation_status,
            ]);

            return redirect()->route('membership-card.show')
                ->with('success', 'Membership card generated successfully!');

        } catch (\Exception $e) {
            \Log::error('Membership card creation error: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to generate membership card. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Display the membership card.
     */
    public function show()
    {
        $user = auth()->user();
        $membershipCard = $user->membershipCard;

        if (!$membershipCard) {
            return redirect()->route('membership-card.create')
                ->with('error', 'Please generate your membership card first.');
        }

        return view('membership-card.show', compact('membershipCard'));
    }

    /**
     * Download membership card as PDF.
     */
     public function downloadPdf()
    {
        $user = auth()->user();
        $membershipCard = $user->membershipCard;

        if (!$membershipCard) {
            return redirect()->route('membership-card.create')
                ->with('error', 'No membership card found.');
        }

        // Use the print view for PDF generation with landscape orientation
        $pdf = Pdf::loadView('membership-card.print', compact('membershipCard'))
                  ->setPaper('a5', 'landscape')
                  ->setOption('dpi', 300)
                  ->setOption('enable-remote', true)
                  ->setOption('enable-local-file-access', true)
                  ->setOption('defaultFont', 'Arial');

        return $pdf->download('YUREI-Membership-Card-' . $membershipCard->membership_number . '.pdf');
    }

    /**
 * Export membership card (PDF download or print view)
 */
public function export($type = 'print')
{
    $user = auth()->user();
    $membershipCard = $user->membershipCard;

    if (!$membershipCard) {
        return redirect()->route('membership-card.create')
            ->with('error', 'No membership card found.');
    }

    if ($type === 'download') {
        // Download as PDF
        $pdf = Pdf::loadView('membership-card.print', compact('membershipCard'))
                  ->setPaper('a5', 'landscape')
                  ->setOption('dpi', 300)
                  ->setOption('enable-remote', true)
                  ->setOption('enable-local-file-access', true)
                  ->setOption('defaultFont', 'Arial');

        return $pdf->download('YUREI-Membership-Card-' . $membershipCard->membership_number . '.pdf');
    } else {
        // Show print view
        return view('membership-card.print', compact('membershipCard'));
    }
}

    /**
     * Display membership card in printable view.
     */
    public function print()
    {
        $user = auth()->user();
        $membershipCard = $user->membershipCard;

        if (!$membershipCard) {
            return redirect()->route('membership-card.create')
                ->with('error', 'No membership card found.');
        }

        return view('membership-card.print', compact('membershipCard'));
    }
}