<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Youth Rescue and Empowerment Initiative</title>
    <title>YUREI Kenya | Youth Rescue and Empowerment Initiative - Youth Empowerment in Meru</title>

    <!-- SEO Meta Description -->
    <meta name="description" content="YUREI (Youth Rescue and Empowerment Initiative) Kenya empowers youth in Meru and across Kenya through education, health, entrepreneurship, community development, and youth association programs. Join us in creating opportunities for young people.">

    <!-- SEO Keywords -->
    <meta name="keywords" content="YUREI Kenya, Youth Rescue and Empowerment Initiative, youth association Meru, youth empowerment Kenya, youth in Meru, Meru youth association, youth leadership Kenya, youth mentorship Kenya, youth programs Meru, youth development Kenya, youth education programs Kenya, youth entrepreneurship Meru, youth innovation Kenya, youth advocacy Kenya, youth empowerment NGO Kenya, youth projects Meru, youth health programs Kenya, youth empowerment Africa, Meru community youth, youth training Kenya, youth workshops Meru, youth conferences Kenya, youth rights Kenya, youth voice Meru, youth future Kenya, youth support Kenya, youth impact Kenya, youth in Meru County, youth policy Kenya, youth skills Kenya, youth digital empowerment Kenya, youth jobs Kenya, youth volunteer Meru, youth collaboration Kenya, youth sports Meru, youth culture Kenya, youth arts Meru, youth agriculture Kenya, youth environment Kenya, youth empowerment campaigns Kenya, youth leadership training Meru, youth community projects Meru, youth inclusion Kenya, youth advocacy Meru, youth activism Kenya, youth health Meru, youth entrepreneurship training Kenya, youth technology Kenya, youth empowerment programs Meru, youth employment Kenya, youth vocational training Kenya, youth partnerships Kenya, youth innovation hubs Meru, youth civic engagement Kenya, youth sustainability Kenya, youth peacebuilding Kenya, youth networks Meru, youth organizations Kenya, youth support groups Meru, youth crisis response Kenya, youth financial literacy Kenya, youth resilience Kenya, youth participation Kenya, youth social impact Meru, youth volunteerism Kenya, youth gender equality Kenya, youth SDGs Kenya, youth access to education Kenya, youth empowerment forums Meru, youth development centers Kenya, youth empowerment projects Africa, YUREI youth Meru, YUREI empowerment programs, YUREI Kenya NGO, YUREI youth training Meru, YUREI education Kenya, YUREI entrepreneurship Kenya, YUREI community Meru, YUREI leadership Kenya, YUREI youth projects Kenya, YUREI partnership programs Kenya, YUREI impact Kenya">

    <!-- Open Graph -->
    <meta property="og:title" content="YUREI Kenya - Youth Rescue and Empowerment Initiative">
    <meta property="og:description" content="Empowering youth in Meru and Kenya through education, health, entrepreneurship, and community development programs.">
    <meta property="og:image" content="{{ asset('storage/web_pics/og-image.jpg') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="YUREI Kenya - Youth Empowerment NGO in Meru">
    <meta name="twitter:description" content="YUREI empowers youth in Meru and Kenya through education, health, entrepreneurship, and development programs.">
    <meta name="twitter:image" content="{{ asset('storage/web_pics/og-image.jpg') }}">

    <!-- Favicons -->
    <link href="{{ asset('storage/web_pics/favicon.jpeg') }}" rel="icon" />
    <link href="{{ asset('storage/web_pics/apple-touch-icon.jpeg') }}" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      // Initialize all Swiper sliders
      document.querySelectorAll('.init-swiper').forEach(swiperEl => {
          const config = JSON.parse(swiperEl.querySelector('.swiper-config').textContent);
          new Swiper(swiperEl, config);
      });
  });
  </script>

<style>
   /* Gallery Section Styles */
        .gallery-slider {
            padding: 20px 0 60px 0;
            position: relative;
        }

        .gallery-slider .swiper-slide {
            height: auto;
            display: flex;
        }

        .gallery-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            cursor: pointer;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .gallery-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-card:hover .gallery-image {
            transform: scale(1.05);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .gallery-card:hover .gallery-overlay {
            opacity: 1;
        }

        .overlay-content {
            color: white;
            text-align: center;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .gallery-card:hover .overlay-content {
            transform: translateY(0);
        }

        .overlay-content h5 {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .overlay-content p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .overlay-content .bi-zoom-in {
            font-size: 24px;
            margin-bottom: 5px;
        }

        /* Enhanced Swiper Navigation for Gallery */
        .gallery-slider .swiper-button-prev,
        .gallery-slider .swiper-button-next {
            color: #196762;
            background: rgba(255, 255, 255, 0.95);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            border: 2px solid #196762;
            top: 50%;
            transform: translateY(-50%);
            opacity: 1;
            transition: all 0.3s ease;
        }

        .gallery-slider .swiper-button-prev:hover,
        .gallery-slider .swiper-button-next:hover {
            background: #196762;
            color: white;
            transform: translateY(-50%) scale(1.1);
        }

        .gallery-slider .swiper-button-prev:after,
        .gallery-slider .swiper-button-next:after {
            font-size: 20px;
            font-weight: bold;
        }

        .gallery-slider .swiper-button-prev {
            left: 10px;
        }

        .gallery-slider .swiper-button-next {
            right: 10px;
        }

        .gallery-slider .swiper-pagination {
            bottom: 0;
        }

        .gallery-slider .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: #ddd;
            opacity: 0.7;
        }

        .gallery-slider .swiper-pagination-bullet-active {
            background: #196762;
            opacity: 1;
            transform: scale(1.2);
        }

        /* Image Modal Styles */
        #imageModal .modal-content {
            background: transparent;
            border: none;
        }

        #imageModal .modal-header {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 1060;
            padding: 1rem;
        }

        #imageModal .btn-close {
            background-size: 1.5rem;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        #imageModal .btn-close:hover {
            opacity: 1;
        }

        #imageModal .modal-body {
            padding: 0;
        }

        #modalImage {
            transition: transform 0.3s ease;
        }

        #modalImage:hover {
            transform: scale(1.02);
        }

        #modalContent {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #modalTitle {
            color: #2a2a2a;
            font-size: 2rem;
            font-weight: 700;
        }

        #modalDescription {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .image-details {
            margin-top: auto;
        }

        /* Responsive adjustments for gallery */
        @media (max-width: 768px) {
            .gallery-slider .swiper-button-prev,
            .gallery-slider .swiper-button-next {
                display: none;
            }
            
            .gallery-slider {
                padding: 20px 0 40px 0;
            }
            
            #imageModal .modal-dialog {
                margin: 0;
            }
            
            #imageModal .col-lg-4 {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.95);
                max-height: 50vh;
                overflow-y: auto;
            }
            
            #modalTitle {
                font-size: 1.5rem;
            }
            
            #modalDescription {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .gallery-card {
                max-width: 100%;
                margin: 0 10px;
            }
            
            .overlay-content h5 {
                font-size: 16px;
            }
            
            .overlay-content p {
                font-size: 12px;
            }
        }

        /* Current Image Preview in Edit Form */
        .current-image-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background: #f8f9fa;
        }

        .current-image-container img {
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
            /* Events Carousel Styles */
        .events-slider {
            padding: 20px 0 60px 0;
            position: relative;
        }

        .events-slider .swiper-slide {
            height: auto;
            display: flex;
        }

        .events-slider .event-card {
            width: 100%;
            margin: 0;
        }

        /* Enhanced Swiper Navigation for Events */
        .events-slider .swiper-button-prev,
        .events-slider .swiper-button-next {
            color: #196762;
            background: rgba(255, 255, 255, 0.95);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            border: 2px solid #196762;
            top: 50%;
            transform: translateY(-50%);
            opacity: 1;
            transition: all 0.3s ease;
        }

        .events-slider .swiper-button-prev:hover,
        .events-slider .swiper-button-next:hover {
            background: #196762;
            color: white;
            transform: translateY(-50%) scale(1.1);
        }

        .events-slider .swiper-button-prev:after,
        .events-slider .swiper-button-next:after {
            font-size: 20px;
            font-weight: bold;
        }

        .events-slider .swiper-button-prev {
            left: 10px;
        }

        .events-slider .swiper-button-next {
            right: 10px;
        }

        .events-slider .swiper-pagination {
            bottom: 0;
        }

        .events-slider .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: #ddd;
            opacity: 0.7;
        }

        .events-slider .swiper-pagination-bullet-active {
            background: #196762;
            opacity: 1;
            transform: scale(1.2);
        }

        /* Adjust event card for carousel */
        .events-slider .event-card {
            animation: float 8s ease-in-out infinite;
            transform-origin: center bottom;
        }

        .events-slider .event-card:hover {
            animation: none;
        }

        /* Responsive adjustments for events carousel */
        @media (max-width: 768px) {
            .events-slider .swiper-button-prev,
            .events-slider .swiper-button-next {
                display: none;
            }
            
            .events-slider {
                padding: 20px 0 40px 0;
            }
        }

        @media (max-width: 576px) {
            .events-slider .event-card {
                max-width: 100%;
                margin: 0 10px;
            }
        }
                /* Resources Carousel Styles */
            .resources-slider {
                padding: 20px 0 60px 0;
                position: relative;
            }

            .resources-slider .swiper-slide {
                height: auto;
                display: flex;
            }

            .resources-slider .news-card {
                width: 100%;
                margin: 0;
            }

            /* Enhanced Swiper Navigation for Resources */
            .resources-slider .swiper-button-prev,
            .resources-slider .swiper-button-next {
                color: #196762;
                background: rgba(255, 255, 255, 0.95);
                width: 50px;
                height: 50px;
                border-radius: 50%;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
                border: 2px solid #196762;
                top: 50%;
                transform: translateY(-50%);
                opacity: 1;
                transition: all 0.3s ease;
            }

            .resources-slider .swiper-button-prev:hover,
            .resources-slider .swiper-button-next:hover {
                background: #196762;
                color: white;
                transform: translateY(-50%) scale(1.1);
            }

            .resources-slider .swiper-button-prev:after,
            .resources-slider .swiper-button-next:after {
                font-size: 20px;
                font-weight: bold;
            }

            .resources-slider .swiper-button-prev {
                left: 10px;
            }

            .resources-slider .swiper-button-next {
                right: 10px;
            }

            .resources-slider .swiper-pagination {
                bottom: 0;
            }

            .resources-slider .swiper-pagination-bullet {
                width: 12px;
                height: 12px;
                background: #ddd;
                opacity: 0.7;
            }

            .resources-slider .swiper-pagination-bullet-active {
                background: #196762;
                opacity: 1;
                transform: scale(1.2);
            }

            /* Adjust news card for carousel */
            .resources-slider .news-card {
                animation: float 8s ease-in-out infinite;
                transform-origin: center bottom;
            }

            .resources-slider .news-card:hover {
                animation: none;
            }

            /* Responsive adjustments for resources carousel */
            @media (max-width: 768px) {
                .resources-slider .swiper-button-prev,
                .resources-slider .swiper-button-next {
                    display: none;
                }
                
                .resources-slider {
                    padding: 20px 0 40px 0;
                }
            }

            @media (max-width: 576px) {
                .resources-slider .news-card {
                    max-width: 100%;
                    margin: 0 10px;
                }
            }
                /* Events Carousel Styles */
            .events-slider {
                padding: 20px 0;
            }

            .event-card {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transition: all 0.3s ease;
                height: 100%;
            }

            .event-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            }

            .event-image-wrapper {
                position: relative;
                overflow: hidden;
            }

            .event-image {
                width: 100%;
                height: 250px;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .event-card:hover .event-image {
                transform: scale(1.05);
            }

            .event-date-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #196762;
                color: white;
                padding: 10px;
                border-radius: 8px;
                text-align: center;
                max-width: 60px;
            }

            .event-day {
                display: block;
                font-size: 24px;
                font-weight: 700;
                line-height: 1;
            }

            .event-month {
                display: block;
                font-size: 14px;
                font-weight: 600;
                text-transform: uppercase;
            }

            .event-content {
                padding: 25px;
            }

            .event-content h3 {
                font-size: 20px;
                font-weight: 700;
                color: #2a2a2a;
                margin-bottom: 10px;
                font-family: "Poppins", sans-serif;
            }

            .event-meta {
                margin-bottom: 15px;
            }

            .event-meta span {
                color: #6c757d;
                font-size: 14px;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
            }

            .event-content p {
                color: #555;
                margin-bottom: 20px;
                line-height: 1.6;
                font-family: "Open Sans", sans-serif;
            }

            .event-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .event-category {
                background: #e9f5f4;
                color: #196762;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
            }

            /* Registration Section Styles */
            .registration-card {
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                padding: 30px;
            }

            .registration-image img {
                width: 100%;
                height: 400px;
                object-fit: cover;
            }

            .registration-content h3 {
                font-size: 28px;
                font-weight: 700;
                color: #2a2a2a;
                margin-bottom: 15px;
                font-family: "Poppins", sans-serif;
            }

            .registration-content p {
                color: #6c757d;
                margin-bottom: 25px;
                font-size: 16px;
                line-height: 1.6;
                font-family: "Open Sans", sans-serif;
            }

            .registration-form .form-label {
                font-weight: 600;
                color: #2a2a2a;
                margin-bottom: 8px;
                font-family: "Poppins", sans-serif;
            }

            .registration-form .form-control,
            .registration-form .form-select {
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 12px 15px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            .registration-form .form-control:focus,
            .registration-form .form-select:focus {
                border-color: #196762;
                box-shadow: 0 0 0 0.2rem rgba(25, 103, 98, 0.25);
            }

            /* Swiper Navigation for Events */
            .events-slider .swiper-button-prev,
            .events-slider .swiper-button-next {
                color: #196762;
                background: rgba(255, 255, 255, 0.9);
                width: 50px;
                height: 50px;
                border-radius: 50%;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .events-slider .swiper-button-prev:after,
            .events-slider .swiper-button-next:after {
                font-size: 20px;
            }

            .events-slider .swiper-pagination-bullet-active {
                background: #196762;
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .registration-card {
                    padding: 20px;
                }
                
                .registration-image img {
                    height: 250px;
                    margin-bottom: 20px;
                }
                
                .registration-content h3 {
                    font-size: 24px;
                }
                
                .event-content {
                    padding: 20px;
                }
                
                .event-content h3 {
                    font-size: 18px;
                }
                
                .event-footer {
                    flex-direction: column;
                    gap: 10px;
                    align-items: flex-start;
                }
            }

            @media (max-width: 576px) {
                .events-slider .swiper-button-prev,
                .events-slider .swiper-button-next {
                    display: none;
                }
            }
            /* Team Section Styles to match yurei.co.ke */
            .team .section-title h2 {
                font-size: 14px;
                font-weight: 500;
                padding: 0;
                line-height: 1px;
                margin: 0 0 5px 0;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: #aaaaaa;
                font-family: "Poppins", sans-serif;
                text-align: center;
            }

            .team .section-title h2::after {
                content: "";
                width: 120px;
                height: 1px;
                display: inline-block;
                background: #196762;
                margin: 4px 10px;
            }

            .team .section-title div {
                text-align: center;
                margin-bottom: 40px;
                position: relative;
            }

            .team .section-title div span {
                font-size: 36px;
                font-weight: 700;
                text-transform: uppercase;
                font-family: "Poppins", sans-serif;
                color: #2a2a2a;
            }

            .team .section-title div .description-title {
                color: #196762;
            }

            .team-card {
                text-align: center;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0px 0 30px rgba(0, 0, 0, 0.1);
                padding: 20px;
                height: 100%;
                transition: 0.3s;
            }

            .team-card:hover {
                transform: translateY(-10px);
            }

            .team-image {
                overflow: hidden;
                border-radius: 10px;
                margin-bottom: 15px;
            }

            .team-image img {
                height: 250px;
                width: 100%;
                object-fit: cover;
                transition: 0.3s;
            }

            .team-card:hover .team-image img {
                transform: scale(1.1);
            }

            .team-content h3 {
                font-weight: 700;
                font-size: 20px;
                margin-bottom: 5px;
                color: #2a2a2a;
                font-family: "Poppins", sans-serif;
                text-align: center;
            }

            .team-content span {
                display: block;
                font-size: 15px;
                padding-bottom: 10px;
                position: relative;
                font-weight: 500;
                color: #196762;
                font-family: "Poppins", sans-serif;
                text-align: center;
            }

            .team-content span::after {
                content: "";
                position: absolute;
                display: block;
                width: 50px;
                height: 1px;
                background: #196762;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
            }

            .team-content p {
                font-style: italic;
                font-size: 14px;
                padding-top: 15px;
                line-height: 26px;
                color: #555;
                text-align: center;
                font-family: "Open Sans", sans-serif;
            }

            /* Swiper Styles */
            .team-slider {
                padding: 20px 0;
            }

            .swiper-button-prev,
            .swiper-button-next {
                color: #196762;
            }

            .swiper-pagination-bullet-active {
                background: #196762;
            }

            /* Donation Section Styles to match yurei.co.ke */
            .donation .section-title h2 {
                font-size: 14px;
                font-weight: 500;
                padding: 0;
                line-height: 1px;
                margin: 0 0 5px 0;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: #aaaaaa;
                font-family: "Poppins", sans-serif;
                text-align: center;
            }

            .donation .section-title h2::after {
                content: "";
                width: 120px;
                height: 1px;
                display: inline-block;
                background: #196762;
                margin: 4px 10px;
            }

            .donation .section-title div {
                text-align: center;
                margin-bottom: 40px;
                position: relative;
            }

            .donation .section-title div span {
                font-size: 36px;
                font-weight: 700;
                text-transform: uppercase;
                font-family: "Poppins", sans-serif;
                color: #2a2a2a;
            }

            .donation .section-title div .description-title {
                color: #196762;
            }

            .donation-intro h3 {
                font-size: 28px;
                font-weight: 700;
                color: #2a2a2a;
                margin-bottom: 15px;
                font-family: "Poppins", sans-serif;
            }

            .donation-intro p {
                font-size: 18px;
                color: #6c757d;
                font-family: "Open Sans", sans-serif;
            }

            /* Payment Card Styles */
            .payment-card {
                position: relative;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
                transition: all 0.4s ease;
                background: #fff;
                height: 100%;
                border: 1px solid rgba(0, 0, 0, 0.05);
                padding: 20px;
                text-align: center;
            }

            .payment-header {
                margin-bottom: 20px;
            }

            .bank-logo {
                height: 40px;
                margin-bottom: 10px;
                object-fit: contain;
            }

            .payment-header h4 {
                font-size: 18px;
                color: #2a2a2a;
                font-weight: 700;
                font-family: "Poppins", sans-serif;
            }

            .payment-content {
                padding: 0 10px;
            }

            .payment-method {
                margin-bottom: 15px;
            }

            .payment-method p {
                font-weight: 600;
                margin-bottom: 8px;
                color: #2a2a2a;
                font-family: "Poppins", sans-serif;
            }

            .payment-method ul {
                list-style: none;
                padding: 0;
            }

            .payment-method li {
                margin-bottom: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                color: #6c757d;
                font-family: "Open Sans", sans-serif;
            }

            .paybill-number {
                font-size: 24px;
                font-weight: 700;
                color: #196762;
                margin: 10px 0;
                font-family: "Poppins", sans-serif;
            }

            .payment-details {
                text-align: left;
                margin: 15px 0;
                padding: 10px;
                background: #f8f9fa;
                border-radius: 8px;
                font-family: "Open Sans", sans-serif;
            }

            .payment-details p {
                margin-bottom: 5px;
                color: #2a2a2a;
            }

            .payment-details strong {
                color: #196762;
            }

            .payment-note {
                font-size: 13px;
                color: #6c757d;
                margin-top: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
                font-family: "Open Sans", sans-serif;
            }

            /* Impact Cards */
            .impact-card {
                text-align: center;
                padding: 25px 15px;
                border-radius: 10px;
                background: #fff;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                height: 100%;
                transition: all 0.3s ease;
            }

            .impact-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .impact-icon {
                width: 60px;
                height: 60px;
                background: #f0f5ff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 15px;
                color: #196762;
                font-size: 24px;
            }

            .impact-card h5 {
                font-size: 18px;
                margin-bottom: 10px;
                color: #2a2a2a;
                font-weight: 700;
                font-family: "Poppins", sans-serif;
            }

            .impact-card p {
                color: #6c757d;
                font-size: 14px;
                font-family: "Open Sans", sans-serif;
            }

            /* Floating Animation */
            @keyframes float {
                0% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-8px);
                }
                100% {
                    transform: translateY(0px);
                }
            }

            .floating-card {
                animation: float 6s ease-in-out infinite;
            }

            .payment-card:nth-child(2) {
                animation-delay: 1s;
            }

            .payment-card:nth-child(3) {
                animation-delay: 2s;
            }

            .floating-card:hover {
                animation: none;
                transform: translateY(-5px);
                box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
            }

            /* Contact Section Styles to match yurei.co.ke */
            .contact .section-title h2 {
                font-size: 14px;
                font-weight: 500;
                padding: 0;
                line-height: 1px;
                margin: 0 0 5px 0;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: #aaaaaa;
                font-family: "Poppins", sans-serif;
                text-align: center;
            }

            .contact .section-title h2::after {
                content: "";
                width: 120px;
                height: 1px;
                display: inline-block;
                background: #196762;
                margin: 4px 10px;
            }

            .contact .section-title div {
                text-align: center;
                margin-bottom: 40px;
                position: relative;
            }

            .contact .section-title div span {
                font-size: 36px;
                font-weight: 700;
                text-transform: uppercase;
                font-family: "Poppins", sans-serif;
                color: #2a2a2a;
            }

            .contact .section-title div .description-title {
                color: #196762;
            }

            .contact-info {
                height: 100%;
            }

            .contact-card {
                background: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
                height: 100%;
            }

            .contact-card h3 {
                font-size: 22px;
                font-weight: 700;
                margin-bottom: 15px;
                color: #2a2a2a;
                font-family: "Poppins", sans-serif;
            }

            .contact-card p {
                color: #6c757d;
                margin-bottom: 30px;
                font-family: "Open Sans", sans-serif;
            }

            .contact-details {
                margin-bottom: 30px;
            }

            .contact-item {
                display: flex;
                align-items: flex-start;
                margin-bottom: 25px;
            }

            .contact-item i {
                font-size: 20px;
                color: #196762;
                margin-right: 15px;
                margin-top: 5px;
                flex-shrink: 0;
            }

            .contact-item div h4 {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 5px;
                color: #2a2a2a;
                font-family: "Poppins", sans-serif;
            }

            .contact-item div p {
                margin: 0;
                color: #6c757d;
                font-family: "Open Sans", sans-serif;
            }

            .social-links {
                display: flex;
                gap: 10px;
            }

            .social-links a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: #f8f9fa;
                color: #196762;
                border-radius: 50%;
                text-decoration: none;
                transition: all 0.3s ease;
                font-size: 18px;
            }

            .social-links a:hover {
                background: #196762;
                color: #fff;
                transform: translateY(-3px);
            }

            /* Contact Form Styles */
            .contact-form-wrapper {
                background: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
                height: 100%;
            }

            .php-email-form .form-group {
                margin-bottom: 20px;
            }

            .php-email-form label {
                font-weight: 600;
                color: #2a2a2a;
                margin-bottom: 8px;
                display: block;
                font-family: "Poppins", sans-serif;
            }

            .php-email-form .form-control {
                height: 50px;
                padding: 10px 15px;
                border: 1px solid #e9ecef;
                border-radius: 5px;
                font-size: 14px;
                transition: all 0.3s ease;
                font-family: "Open Sans", sans-serif;
            }

            .php-email-form .form-control:focus {
                border-color: #196762;
                box-shadow: 0 0 0 0.2rem rgba(25, 103, 98, 0.25);
            }

            .php-email-form textarea.form-control {
                height: auto;
                resize: vertical;
            }

            .php-email-form button[type="submit"] {
                background: #196762;
                border: 0;
                padding: 12px 40px;
                color: #fff;
                transition: 0.4s;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 600;
                font-family: "Poppins", sans-serif;
            }

            .php-email-form button[type="submit"]:hover {
                background: #14524d;
                transform: translateY(-2px);
            }
            /* Hero Section Auth Buttons Styling - Mobile/Tablet only */
                .hero-auth-buttons {
                    display: flex;
                    gap: 10px;
                    align-items: center;
                    flex-wrap: wrap;
                }

                .hero-auth-buttons .btn {
                    border-radius: 5px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    padding: 8px 16px;
                }

                .hero-auth-buttons .btn-outline-light {
                    border: 1px solid rgba(255, 255, 255, 0.5);
                    color: #fff;
                }

                .hero-auth-buttons .btn-outline-light:hover {
                    background: #fff;
                    color: #196762;
                    border-color: #fff;
                }

                .hero-auth-buttons .btn-light {
                    background: #fff;
                    color: #196762;
                    border: 1px solid #fff;
                }

                .hero-auth-buttons .btn-light:hover {
                    background: transparent;
                    color: #fff;
                    border-color: #fff;
                }

                /* News Card Floating Styles */
                .news-card {
                position: relative;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                background: #fff;
                height: 100%;
                display: flex;
                flex-direction: column;
                border: 1px solid rgba(0, 0, 0, 0.05);
                }

                .news-card:hover {
                transform: translateY(-10px) scale(1.02);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
                }

                .news-image-wrapper {
                position: relative;
                overflow: hidden;
                height: 220px;
                }

                .news-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                }

                .download-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                opacity: 0;
                transition: opacity 0.4s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                }

                .overlay-content {
                color: white;
                transform: translateY(20px);
                transition: transform 0.4s ease;
                }

                .overlay-content h4 {
                font-size: 18px;
                margin-bottom: 15px;
                color: #fff;
                }

                .overlay-content ul {
                list-style: none;
                padding: 0;
                }

                .overlay-content li {
                margin-bottom: 8px;
                font-size: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
                }

                .news-card:hover .download-overlay {
                opacity: 1;
                }

                .news-card:hover .overlay-content {
                transform: translateY(0);
                }

                .news-card:hover .news-image {
                transform: scale(1.1);
                }

                .file-badge {
                position: absolute;
                top: 20px;
                right: 20px;
                background: rgba(255, 255, 255, 0.95);
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                color: #2a2a2a;
                display: flex;
                align-items: center;
                gap: 6px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                z-index: 2;
                }

                .news-content {
                padding: 25px;
                flex: 1;
                display: flex;
                flex-direction: column;
                }

                .news-category-badge {
                position: absolute;
                top: 5px;
                left: 20px;
                background: #196762;
                color: white;
                padding: 6px 16px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                box-shadow: 0 4px 8px rgba(25, 103, 98, 0.2);
                }

                .news-content h3 {
                margin: 15px 0 10px;
                font-size: 22px;
                color: #2a2a2a;
                font-weight: 700;
                font-family: "Poppins", sans-serif;
                }

                .news-content p {
                color: #6c757d;
                margin-bottom: 20px;
                flex: 1;
                line-height: 1.6;
                font-family: "Open Sans", sans-serif;
                }

                .news-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: auto;
                }

                .floating-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                text-decoration: none !important;
                background: var(--primary-color);
                color: white;
                border: none;
                }

                .floating-btn i {
                transition: transform 0.3s ease;
                }

                .news-card:hover .floating-btn {
                background: var(--accent-color);
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(25, 103, 98, 0.3);
                }

                .news-card:hover .floating-btn i {
                transform: translateY(2px);
                }

                .file-size {
                font-size: 13px;
                color: #6c757d;
                display: flex;
                align-items: center;
                gap: 5px;
                font-family: "Open Sans", sans-serif;
                }

                /* Floating Animation */
                @keyframes float {
                0% {
                    transform: translateY(0px) rotate(0deg);
                }
                50% {
                    transform: translateY(-12px) rotate(1deg);
                }
                100% {
                    transform: translateY(0px) rotate(0deg);
                }
                }

                .news-card {
                animation: float 8s ease-in-out infinite;
                transform-origin: center bottom;
                }

                .news-card:nth-child(2) {
                animation-delay: 1.5s;
                }

                .news-card:nth-child(3) {
                animation-delay: 3s;
                }

                .news-card:hover {
                animation: none;
                }



            /* Responsive Adjustments */
            @media (max-width: 992px) {
                .news-image-wrapper {
                    height: 200px;
                }

                .news-content h3 {
                    font-size: 20px;
                }
                .contact-card,
                .contact-form-wrapper {
                    margin-bottom: 30px;
                }
                
                .contact-card {
                    padding: 25px;
                }
                
                .contact-form-wrapper {
                    padding: 25px;
                }
                
            }

            /* Responsive Adjustments */
            @media (min-width: 992px) {
                
                .hero-auth-buttons {
                    display: none !important;
                }
            }


            @media (max-width: 768px) {
                .news-card {
                    max-width: 400px;
                    margin: 0 auto;
                }
                .team .section-title div span {
                    font-size: 28px;
                }
                
                .team-content h3 {
                    font-size: 18px;
                }
                
                .team-content span {
                    font-size: 14px;
                }

                .payment-card {
                    margin-bottom: 20px;
                }
                
                .donation-intro h3 {
                    font-size: 24px;
                }
                
                .donation-intro p {
                    font-size: 16px;
                }
                
                .payment-header h4 {
                    font-size: 16px;
                }
                
                .paybill-number {
                    font-size: 20px;
                }

                .contact .section-title div span {
                    font-size: 28px;
                }
                
                .contact-card h3 {
                    font-size: 20px;
                }
                
                .contact-item {
                    flex-direction: column;
                    text-align: center;
                }
                
                .contact-item i {
                    margin-right: 0;
                    margin-bottom: 10px;
                }
                
                .social-links {
                    justify-content: center;
                }
                .hero-auth-buttons {
                    justify-content: center;
                }
                
                .hero-auth-buttons .btn {
                    padding: 10px 20px;
                    font-size: 14px;
                }
            }

            @media (max-width: 576px) {
                .contact-card,
                .contact-form-wrapper {
                    padding: 20px;
                }
                
                .contact .section-title div span {
                    font-size: 24px;
                }
                .donation .section-title div span {
                    font-size: 28px;
                }
                
                .donation-intro h3 {
                    font-size: 22px;
                }
                .hero-auth-buttons {
                    flex-direction: column;
                    width: 100%;
                }
                
                .hero-auth-buttons .btn {
                    width: 100%;
                    margin: 5px 0;
                    text-align: center;
                }
            }
</style>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="index-page">
    <!-- The rest of your welcome.blade.php content remains exactly the same -->
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark header fixed-top">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#hero">
          <img src="{{asset('assets/images/yurei-036.jpeg')}}" alt="YUREI Logo" height="40" class="me-2 rounded">
          <span class="sitename fw-bold">YUREI</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link active" href="#hero">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="#programs">Programs</a></li>
            <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="#events">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="#news">Resources</a></li>
            <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
            <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="#donate">Donate</a></li>
          </ul>
          
          <div class="auth-buttons">
                @auth
                    @if(auth()->user()->role == 1)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                            <i class="bi bi-speedometer2 me-1"></i>Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm my-2">
                        <i class="bi bi-person-plus me-1"></i>Sign Up
                    </a>
                @endauth
            </div>
        </div>
      </div>
    </nav>

    <!-- The rest of your content... -->

    <main>
      <!-- Hero Section -->
      <section id="hero" class="hero section">
          <div class="container">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <div class="hero-content">
                          <h2>Empowering Youth for a Brighter Future</h2>
                          <p>
                              Youth Rescue and Empowerment Initiative is dedicated to
                              transforming lives through education, skills development, and
                              community support.
                          </p>
                          <div class="hero-btns">
                              <a href="#programs" class="btn btn-primary">Our Programs</a>
                              <a href="#donate" class="btn btn-outline-light">Donate Now</a>
                          </div>
                          
                          <!-- Auth Buttons for Hero Section -->
                          <div class="hero-auth-buttons mt-4">
                              <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                                  <i class="bi bi-box-arrow-in-right me-1"></i>Login
                              </a>
                              <a href="{{ route('register') }}" class="btn btn-light btn-sm my-2">
                                  <i class="bi bi-person-plus me-1"></i>Sign Up
                              </a>
                          </div>

                          <div class="hero-stats">
                              <div class="stat-item">
                                  <h3>1000+</h3>
                                  <p>Youth Impacted</p>
                              </div>
                              <div class="stat-item">
                                  <h3>50+</h3>
                                  <p>Communities Reached</p>
                              </div>
                              <div class="stat-item">
                                  <h3>100%</h3>
                                  <p>Dedication</p>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="hero-image">
                          <img
                              src="{{ asset('assets/images/yurei-024.jpeg') }}"
                              alt="Youth Empowerment"
                              loading="lazy"
                              class="img-fluid rounded shadow"
                          />
                      </div>
                  </div>
              </div>
          </div>
      </section>

      <!-- About Section -->
      <section id="about" class="about section">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="about-image">
                <img
                  src="{{ asset('assets/images/yurei-002.jpeg') }}"
                  alt="About Our Organization"
                  loading="lazy"
                  class="img-fluid rounded shadow"
                />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="about-content">
                <h2>About Youth Rescue and Empowerment Initiative</h2>
                <p class="lead">
                  We are a non-profit organization committed to rescuing and
                  empowering disadvantaged youth through education, skills
                  acquisition, and mentorship programs.
                </p>
                <h3>Our Mission</h3>
                <p>
                  To provide at-risk youth with the tools, resources, and
                  support they need to overcome challenges and achieve their
                  full potential.
                </p>
                <h3>Our Vision</h3>
                <p>
                  A world where every young person has equal opportunities to
                  succeed regardless of their background or circumstances.
                </p>
                <div class="mt-4">
                  <a href="#programs" class="btn btn-primary">Learn About Our Programs</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Programs Section -->
      <section id="programs" class="services section light-background">
        <div class="container section-title">
          <h2>Our Programs</h2>
          <div>
            <span>Explore Our</span>
            <span class="description-title">Focus Areas</span>
          </div>
        </div>

        <div class="container">
          <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
              <div class="service-card">
                <div class="icon-box">
                  <i class="bi bi-book"></i>
                </div>
                <h3>Education</h3>
                <p>
                  Providing access to quality education, scholarships, and
                  learning resources for underprivileged youth.
                </p>
                <ul class="service-features list-unstyled">
                  <li><i class="bi bi-check-circle text-success me-2"></i> Scholarship Programs</li>
                  <li><i class="bi bi-check-circle text-success me-2"></i> Tutoring Services</li>
                  <li><i class="bi bi-check-circle text-success me-2"></i> School Supplies</li>
                </ul>
              </div>
            </div>

            <div class="col-lg-4 col-md-6">
              <div class="service-card">
                <div class="icon-box">
                  <i class="bi bi-heart-pulse"></i>
                </div>
                <h3>Health</h3>
                <p>
                  Promoting physical and mental health through awareness
                  campaigns and access to healthcare services.
                </p>
                <ul class="service-features list-unstyled">
                  <li><i class="bi bi-check-circle text-success me-2"></i> Health Education</li>
                  <li><i class="bi bi-check-circle text-success me-2"></i> Medical Checkups</li>
                  <li><i class="bi bi-check-circle text-success me-2"></i> Counseling Services</li>
                </ul>
              </div>
            </div>

            <div class="col-lg-4 col-md-6">
              <div class="service-card">
                <div class="icon-box">
                  <i class="bi bi-briefcase"></i>
                </div>
                <h3>Entrepreneurship</h3>
                <p>
                  Equipping youth with business skills and startup support to
                  become self-sufficient.
                </p>
                <ul class="service-features list-unstyled">
                  <li><i class="bi bi-check-circle text-success me-2"></i> Business Training</li>
                  <li><i class="bi bi-check-circle text-success me-2"></i> Mentorship</li>
                  <li><i class="bi bi-check-circle text-success me-2"></i> Startup Grants</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

     <!-- Gallery Section -->
        <section id="gallery" class="gallery section light-background">
            <div class="container section-title" data-aos="fade-up">
                <h2>Our Gallery</h2>
                <div>
                    <span>View Our</span>
                    <span class="description-title">Memories</span>
                </div>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                @php
                    $galleryImages = App\Models\Gallery::active()->latest()->take(12)->get();
                @endphp
                
                @if($galleryImages->count() > 0)
                    <div class="gallery-slider swiper init-swiper">
                        <script type="application/json" class="swiper-config">
                            {
                                "loop": true,
                                "speed": 800,
                                "autoplay": {
                                    "delay": 4000
                                },
                                "slidesPerView": 1,
                                "spaceBetween": 20,
                                "pagination": {
                                    "el": ".swiper-pagination",
                                    "type": "bullets",
                                    "clickable": true
                                },
                                "navigation": {
                                    "nextEl": ".swiper-button-next",
                                    "prevEl": ".swiper-button-prev"
                                },
                                "breakpoints": {
                                    "576": {
                                        "slidesPerView": 2
                                    },
                                    "768": {
                                        "slidesPerView": 3
                                    },
                                    "992": {
                                        "slidesPerView": 4
                                    }
                                }
                            }
                        </script>
                        <div class="swiper-wrapper">
                            @foreach($galleryImages as $image)
                                <div class="swiper-slide">
                                    <div class="gallery-card" onclick="openImageModal('{{ Storage::disk('public')->url($image->image_path) }}', '{{ $image->title }}', '{{ $image->description }}')">
                                        <div class="gallery-image-wrapper">
                                            <img src="{{ Storage::disk('public')->url($image->image_path) }}" 
                                                class="img-fluid gallery-image" 
                                                loading="lazy"
                                                alt="{{ $image->title }}"
                                                style="height: 250px; width: 100%; object-fit: cover;">
                                            <div class="gallery-overlay">
                                                <div class="overlay-content">
                                                    <h5>{{ $image->title }}</h5>
                                                    <p class="small">{{ Str::limit($image->description, 60) }}</p>
                                                    <span class="badge bg-primary">{{ $image->category }}</span>
                                                    <div class="mt-2">
                                                        <i class="bi bi-zoom-in"></i> Click to enlarge
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                @else
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No gallery images available. Check back later!
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content bg-dark">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center justify-content-center p-0">
                        <div class="container-fluid h-100">
                            <div class="row h-100">
                                <div class="col-12 col-lg-8 d-flex align-items-center justify-content-center">
                                    <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 90vh; object-fit: contain;">
                                </div>
                                <div class="col-12 col-lg-4 bg-light text-dark p-4">
                                    <div id="modalContent">
                                        <h3 id="modalTitle" class="fw-bold mb-3"></h3>
                                        <p id="modalDescription" class="mb-4"></p>
                                        <div class="image-details">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-primary" id="modalCategory"></span>
                                                <button class="btn btn-outline-primary btn-sm" onclick="downloadImage()">
                                                    <i class="bi bi-download me-1"></i>Download
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Section -->
        <section id="events" class="events section">
            <div class="container section-title">
                <h2>Upcoming Events</h2>
                <div><span>Join Our</span> <span class="description-title">Events</span></div>
            </div>

            <div class="container">
                @php
                    $events = App\Models\Event::where('event_date', '>=', now())
                        ->orderBy('event_date')
                        ->get();
                @endphp
                
                @if($events->count() > 0)
                    <div class="events-slider swiper init-swiper">
                        <script type="application/json" class="swiper-config">
                            {
                                "loop": true,
                                "speed": 800,
                                "autoplay": {
                                    "delay": 5000
                                },
                                "slidesPerView": 1,
                                "spaceBetween": 30,
                                "pagination": {
                                    "el": ".swiper-pagination",
                                    "type": "bullets",
                                    "clickable": true
                                },
                                "navigation": {
                                    "nextEl": ".swiper-button-next",
                                    "prevEl": ".swiper-button-prev"
                                },
                                "breakpoints": {
                                    "576": {
                                        "slidesPerView": 1
                                    },
                                    "768": {
                                        "slidesPerView": 2
                                    },
                                    "992": {
                                        "slidesPerView": 3
                                    },
                                    "1200": {
                                        "slidesPerView": 3
                                    }
                                }
                            }
                        </script>
                        <div class="swiper-wrapper">
                            @foreach($events as $event)
                            <div class="swiper-slide">
                                <div class="event-card h-100">
                                    <div class="event-image-wrapper">
                                        @if($event->image)
                                            <img src="{{ Storage::disk('public')->url($event->image) }}" 
                                                class="img-fluid event-image" 
                                                loading="lazy"
                                                alt="{{ $event->title }}" 
                                                style="height: 250px; width: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('assets/images/default-event.jpg') }}" 
                                                class="img-fluid event-image" 
                                                loading="lazy"
                                                alt="{{ $event->title }}" 
                                                style="height: 250px; width: 100%; object-fit: cover;">
                                        @endif
                                        <div class="event-date-badge">
                                            <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                            <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                        </div>
                                    </div>
                                    <div class="event-content">
                                        <h3>{{ $event->title }}</h3>
                                        <div class="event-meta">
                                            <span>
                                                <i class="bi bi-clock me-1"></i> 
                                                {{ $event->event_date->format('h:i A') }} 
                                                <i class="bi bi-geo-alt ms-2 me-1"></i> 
                                                {{ $event->venue }}
                                            </span>
                                        </div>
                                        <p>{{ Str::limit($event->description, 120) }}</p>
                                        
                                        <!-- Payment Badge -->
                                        @if($event->is_paid)
                                        <div class="mb-2">
                                            <span class="badge bg-warning">KES {{ number_format($event->registration_fee, 2) }}</span>
                                        </div>
                                        @else
                                        <div class="mb-2">
                                            <span class="badge bg-success">Free Event</span>
                                        </div>
                                        @endif
                                        
                                        <div class="event-footer">
                                            <div class="event-category">
                                                @if($event->isRegistrationOpen())
                                                    @if($event->max_participants == 0 || $event->current_participants < $event->max_participants)
                                                        <span class="text-success">Open</span>
                                                    @else
                                                        <span class="text-danger">Full</span>
                                                    @endif
                                                @else
                                                    <span class="text-danger">Closed</span>
                                                @endif
                                            </div>
                                            
                                            @if($event->isRegistrationOpen())
                                                <a href="{{ route('event.registration.form', $event->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-pencil-square me-1"></i>Register
                                                </a>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    Registration Closed
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <!-- Event Capacity Info -->
                                        <div class="event-capacity mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>
                                                Spots: {{ $event->current_participants }}/{{ $event->max_participants ?: '∞' }}
                                                @if($event->hasRegistrationDeadline())
                                                    • Deadline: {{ $event->registration_deadline->format('M d, Y') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                @else
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No upcoming events scheduled. Check back later!
                        </div>
                    </div>
                @endif
            </div>
        </section>

      <!-- Resources Section -->
      <section id="news" class="news section light-background">
          <div class="container section-title" data-aos="fade-up">
              <h2>Official Documents</h2>
              <div>
                  <span>Important</span>
                  <span class="description-title">Resources</span>
              </div>
          </div>

          <div class="container" data-aos="fade-up" data-aos-delay="100">
              @php
                  $documents = App\Models\Document::where('is_active', true)
                      ->orderBy('created_at', 'desc')
                      ->get();
              @endphp
              
              @if($documents->count() > 0)
                  <div class="resources-slider swiper init-swiper">
                      <script type="application/json" class="swiper-config">
                          {
                              "loop": true,
                              "speed": 800,
                              "autoplay": {
                                  "delay": 5000
                              },
                              "slidesPerView": 1,
                              "spaceBetween": 30,
                              "pagination": {
                                  "el": ".swiper-pagination",
                                  "type": "bullets",
                                  "clickable": true
                              },
                              "navigation": {
                                  "nextEl": ".swiper-button-next",
                                  "prevEl": ".swiper-button-prev"
                              },
                              "breakpoints": {
                                  "576": {
                                      "slidesPerView": 2
                                  },
                                  "768": {
                                      "slidesPerView": 2
                                  },
                                  "992": {
                                      "slidesPerView": 3
                                  },
                                  "1200": {
                                      "slidesPerView": 3
                                  }
                              }
                          }
                      </script>
                      <div class="swiper-wrapper">
                          @foreach($documents as $document)
                              <div class="swiper-slide">
                                  <div class="news-card floating-card">
                                      <div class="news-image-wrapper">
                                          @if($document->image_url)
                                              <img
                                                  src="{{ $document->image_url }}"
                                                  class="img-fluid news-image"
                                                  loading="lazy"
                                                  alt="{{ $document->title }}"
                                              />
                                          @else
                                              <img
                                                  src="{{ asset('assets/images/default-document.jpg') }}"
                                                  class="img-fluid news-image"
                                                  loading="lazy"
                                                  alt="{{ $document->title }}"
                                              />
                                          @endif
                                          <div class="download-overlay">
                                              <div class="overlay-content">
                                                  <h4>Document Details:</h4>
                                                  <ul>
                                                      <li style="margin-top: 15px;">
                                                          <i class="bi bi-check-circle"></i> 
                                                          {{ $document->category }}
                                                      </li>
                                                      <li>
                                                          <i class="bi bi-check-circle"></i> 
                                                          {{ $document->formatted_file_size }}
                                                      </li>
                                                      <li>
                                                          <i class="bi bi-check-circle"></i> 
                                                          {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }} File
                                                      </li>
                                                  </ul>
                                              </div>
                                          </div>
                                          <div class="file-badge">
                                              <i class="bi bi-file-earmark-{{ pathinfo($document->file_name, PATHINFO_EXTENSION) }}"></i> 
                                              {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}
                                          </div>
                                      </div>
                                      <div class="news-content">
                                          <div class="news-category-badge">{{ $document->category }}</div>
                                          <h3>{{ $document->title }}</h3>
                                          <p>{{ Str::limit($document->description, 100) }}</p>
                                          <div class="news-footer">
                                              <a
                                                  href="{{ $document->file_url }}"
                                                  class="btn btn-primary floating-btn"
                                                  target="_blank"
                                              >
                                                  <span>Download</span>
                                                  <i class="bi bi-download"></i>
                                              </a>
                                              <span class="file-size">
                                                  <i class="bi bi-hdd"></i> 
                                                  {{ $document->formatted_file_size }}
                                              </span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                      </div>
                      <div class="swiper-pagination"></div>
                      <div class="swiper-button-prev"></div>
                      <div class="swiper-button-next"></div>
                  </div>

                  <!-- View All Documents Button -->
                  @auth
                  <div class="text-center mt-5">
                      <a href="{{ route('documents.index') }}" class="btn btn-outline-primary btn-lg">
                          <i class="bi bi-folder2-open me-2"></i>Manage Documents
                      </a>
                  </div>
                  @endauth
              @else
                  <div class="col-12 text-center">
                      <div class="alert alert-info">
                          <i class="bi bi-info-circle me-2"></i>
                          No documents available. Check back later!
                      </div>
                      @auth
                      <a href="{{ route('documents.create') }}" class="btn btn-primary mt-3">
                          <i class="bi bi-upload me-1"></i>Upload First Document
                      </a>
                      @endauth
                  </div>
              @endif
          </div>
      </section>

      <!-- Team Section -->
      <section id="team" class="team section">
          <div class="container section-title" data-aos="fade-up">
              <h2>Our Team</h2>
              <div>
                  <span>Meet Our</span>
                  <span class="description-title">Leadership</span>
              </div>
          </div>

          <div class="container" data-aos="fade-up" data-aos-delay="100">
              @php
                  $leaders = App\Models\Leadership::active()->ordered()->get();
              @endphp
              
              @if($leaders->count() > 0)
                  <div class="team-slider swiper init-swiper">
                      <script type="application/json" class="swiper-config">
                          {
                              "loop": true,
                              "speed": 800,
                              "autoplay": {
                                  "delay": 5000
                              },
                              "slidesPerView": 1,
                              "spaceBetween": 30,
                              "pagination": {
                                  "el": ".swiper-pagination",
                                  "type": "bullets",
                                  "clickable": true
                              },
                              "navigation": {
                                  "nextEl": ".swiper-button-next",
                                  "prevEl": ".swiper-button-prev"
                              },
                              "breakpoints": {
                                  "576": {
                                      "slidesPerView": 2
                                  },
                                  "992": {
                                      "slidesPerView": 3
                                  }
                              }
                          }
                      </script>
                      <div class="swiper-wrapper" style="height: 410px">
                          @foreach($leaders as $leader)
                              <div class="swiper-slide">
                                  <div class="team-card">
                                      <div class="team-image">
                                          <img
                                              src="{{ $leader->image_url }}"
                                              class="img-fluid"
                                              loading="lazy"
                                              alt="{{ $leader->name }}"
                                              style="height: 200px"
                                          />
                                      </div>
                                      <div class="team-content">
                                          <h3>{{ $leader->name }}</h3>
                                          <span>{{ $leader->position }}</span>
                                          <p>{{ $leader->bio ? Str::limit($leader->bio, 120) : 'No description available.' }}</p>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                      </div>
                      <div class="swiper-pagination"></div>
                      <div class="swiper-button-prev"></div>
                      <div class="swiper-button-next"></div>
                  </div>
              @else
                  <div class="col-12 text-center">
                      <div class="alert alert-info">
                          <i class="bi bi-info-circle me-2"></i>
                          No leadership information available. Check back later!
                      </div>
                  </div>
              @endif
          </div>
      </section>
      <!-- /Team Section -->

     <!-- Donation Section -->
      <section id="donate" class="donation section light-background">
          <div class="container section-title" data-aos="fade-up">
              <h2>Support Our Cause</h2>
              <div>
                  <span>Make a</span> <span class="description-title">Donation</span>
              </div>
          </div>

          <div class="container" data-aos="fade-up" data-aos-delay="100">
              <div class="donation-intro text-center mb-5">
                  <h3>Every donation helps us empower more youth</h3>
                  <p>Choose your preferred payment method below</p>
              </div>

              <!-- Three Floating Payment Cards -->
              <div class="row gy-4 mb-4">
                  <!-- KCB Card -->
                  <div class="col-lg-4 col-md-4" data-aos="fade-up" data-aos-delay="200">
                      <div class="payment-card floating-card">
                          <div class="payment-header">
                              <img
                                  src="{{ asset('assets/images/kcb.png') }}"
                                  loading="lazy"
                                  alt="KCB Bank"
                                  class="bank-logo"
                              />
                              <h4>KCB Payment</h4>
                          </div>
                          <div class="payment-content">
                              <div class="payment-method">
                                  <p><strong>Pay With:</strong></p>
                                  <ul>
                                      <li><i class="bi bi-phone"></i> KCB App</li>
                                      <li><i class="bi bi-telephone"></i> *522#</li>
                                  </ul>
                              </div>
                              <div class="payment-details">
                                  <p><strong>Account Number:</strong> 7794277</p>
                                  <p><strong>Business Name:</strong> YOUTH RESCUE & EMP.</p>
                              </div>
                              <div class="payment-note">
                                  <p><i class="bi bi-check-circle"></i> No charges apply</p>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- M-PESA Card -->
                  <div class="col-lg-4 col-md-4" data-aos="fade-up" data-aos-delay="300">
                      <div class="payment-card floating-card">
                          <div class="payment-header">
                              <img
                                  src="{{ asset('assets/images/mpesa.png') }}"
                                  loading="lazy"
                                  alt="M-PESA"
                                  class="bank-logo"
                              />
                              <h4>M-PESA</h4>
                          </div>
                          <div class="payment-content">
                              <div class="payment-method">
                                  <p><strong>Paybill Number:</strong></p>
                                  <div class="paybill-number">522533</div>
                              </div>
                              <div class="payment-details">
                                  <p><strong>Account Number:</strong> 7794277</p>
                                  <p><strong>Business Name:</strong> YOUTH RESCUE & EMP.</p>
                              </div>
                              <a href="{{ route('donations.form') }}" class="btn btn-success">
            <i class="bi bi-heart me-1"></i>Donate Now
        </a>
                              <div class="payment-note">
                                  <p>
                                      <i class="bi bi-info-circle"></i> Standard charges apply
                                  </p>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- VCOMA Card -->
                  <div class="col-lg-4 col-md-4" data-aos="fade-up" data-aos-delay="400">
                      <div class="payment-card floating-card">
                          <div class="payment-header">
                              <img
                                  src="{{ asset('assets/images/vooma.png') }}"
                                  loading="lazy"
                                  alt="VCOMA"
                                  class="bank-logo"
                              />
                              <h4>VOOMA Payment</h4>
                          </div>
                          <div class="payment-content">
                              <div class="payment-method">
                                  <p><strong>Pay With:</strong></p>
                                  <ul>
                                      <li><i class="bi bi-phone"></i> VOOMA App</li>
                                      <li><i class="bi bi-telephone"></i> *844#</li>
                                  </ul>
                              </div>
                              <div class="payment-details">
                                  <p><strong>Account Number:</strong> 7794277</p>
                                  <p><strong>Business Name:</strong> YOUTH RESCUE & EMP.</p>
                              </div>
                              <div class="payment-note">
                                  <p><i class="bi bi-check-circle"></i> No charges apply</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- How Your Donation Helps Section -->
              <div class="donation-impact-section mt-5" data-aos="fade-up" data-aos-delay="600">
                  <h3 class="text-center mb-4">How Your Donation Helps</h3>
                  <div class="row">
                      <div class="col-md-4">
                          <div class="impact-card">
                              <div class="impact-icon">
                                  <i class="bi bi-book"></i>
                              </div>
                              <h5>Education Support</h5>
                              <p>$50 provides school supplies for one child</p>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="impact-card">
                              <div class="impact-icon">
                                  <i class="bi bi-tools"></i>
                              </div>
                              <h5>Vocational Training</h5>
                              <p>$100 sponsors a youth for skills development</p>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="impact-card">
                              <div class="impact-icon">
                                  <i class="bi bi-heart-pulse"></i>
                              </div>
                              <h5>Health Camps</h5>
                              <p>$500 supports a community health initiative</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- /Donation Section -->

     
      <!-- Contact Section -->
      <section id="contact" class="contact section">
          <div class="container section-title" data-aos="fade-up">
              <h2>Contact Us</h2>
              <div>
                  <span>Get In</span> <span class="description-title">Touch</span>
              </div>
          </div>

          <div class="container" data-aos="fade-up" data-aos-delay="100">
              <div class="row">
                  <div class="col-lg-5">
                      <div class="contact-info">
                          <div class="contact-card">
                              <h3>Contact Information</h3>
                              <p>
                                  We'd love to hear from you! Reach out for inquiries,
                                  partnerships, or volunteer opportunities.
                              </p>

                              <div class="contact-details">
                                  <div class="contact-item">
                                      <i class="bi bi-envelope"></i>
                                      <div>
                                          <h4>Email:</h4>
                                          <p>youthrescueyurei@gmail.com</p>
                                      </div>
                                  </div>

                                  <div class="contact-item">
                                      <i class="bi bi-telephone"></i>
                                      <div>
                                          <h4>Phone:</h4>
                                          <p>0794142078</p>
                                      </div>
                                  </div>
                              </div>

                              <div class="social-links">
                                  <a href="#" target="_blank"><i class="bi bi-facebook"></i></a>
                                  <a href="#" target="_blank"><i class="bi bi-twitter-x"></i></a>
                                  <a href="#" target="_blank"><i class="bi bi-instagram"></i></a>
                                  <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                                  <a href="https://www.tiktok.com/@youth.rescueyurei" target="_blank"><i class="bi bi-tiktok"></i></a>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-7">
                      <div class="contact-form-wrapper">
                          <form action="#" method="post" class="php-email-form">
                              <div class="row">
                                  <div class="col-md-6 form-group">
                                      <label for="name">Your Name</label>
                                      <input
                                          type="text"
                                          name="name"
                                          class="form-control"
                                          id="name"
                                          placeholder="Your Name"
                                          required
                                      />
                                  </div>
                                  <div class="col-md-6 form-group mt-3 mt-md-0">
                                      <label for="email">Your Email</label>
                                      <input
                                          type="email"
                                          class="form-control"
                                          name="email"
                                          id="email"
                                          placeholder="Your Email"
                                          required
                                      />
                                  </div>
                              </div>
                              <div class="form-group mt-3">
                                  <label for="subject">Subject</label>
                                  <input
                                      type="text"
                                      class="form-control"
                                      name="subject"
                                      id="subject"
                                      placeholder="Subject"
                                      required
                                  />
                              </div>
                              <div class="form-group mt-3">
                                  <label for="message">Message</label>
                                  <textarea
                                      class="form-control"
                                      name="message"
                                      rows="7"
                                      placeholder="Your message here..."
                                      required
                                  ></textarea>
                              </div>
                              <div class="text-center mt-4">
                                  <button type="submit">Send Message</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- /Contact Section -->


    </main>

    <!-- Footer -->
    <footer class="footer dark-background py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h5>Youth Rescue and Empowerment Initiative</h5>
            <p>Empowering youth for a brighter future through education, health, and entrepreneurship programs.</p>
            <div class="contact-info">
              <p><i class="bi bi-envelope me-2"></i> youthrescueyurei@gmail.com</p>
              <p><i class="bi bi-phone me-2"></i> 0794142078</p>
            </div>
          </div>
          <div class="col-lg-6 text-lg-end">
            <div class="social-links mt-5">
              <h5 style="align-self: flex-start;">Follow Us</h5>
              <a href="#" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-facebook"></i></a>
              <a href="#" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-twitter"></i></a>
              <a href="#" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-instagram"></i></a>
              <a href="#" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-linkedin"></i></a>
              <a href="https://www.tiktok.com/@youth.rescueyurei" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-tiktok"></i></a>
            </div>
          </div>
        </div>
        <hr class="my-4">
        <div class="row">
          <div class="col-md-6">
            <p class="mb-0">&copy; {{ date('Y') }} Youth Rescue and Empowerment Initiative. All Rights Reserved.</p>
          </div>
          <div class="col-md-6 text-md-end">
            <p class="mb-0">Designed with <i class="bi bi-heart-fill text-danger"></i> for the youth</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Payment Success Modal -->
<div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-labelledby="paymentSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentSuccessModalLabel">
                    <i class="bi bi-check-circle-fill me-2"></i>Registration Successful!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-envelope-check text-success" style="font-size: 3rem;"></i>
                </div>
                <h4 class="text-success mb-3">Thank You for Registering!</h4>
                <p class="mb-3" id="modalMessage">Your payment was successful and your registration has been confirmed.</p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Check your email</strong> for the event invitation and confirmation details.
                </div>
                <p class="text-muted small mt-3">
                    If you don't see the email, please check your spam folder.
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-1"></i>Got It!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Failed Modal -->
<div class="modal fade" id="paymentFailedModal" tabindex="-1" aria-labelledby="paymentFailedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="paymentFailedModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Payment Failed
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                </div>
                <h4 class="text-danger mb-3">Payment Not Completed</h4>
                <p class="mb-3" id="failedModalMessage">We encountered an issue with your payment.</p>
                <div class="alert alert-warning">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    <strong>Please try again</strong> - The page will reload for you to restart the registration process.
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Try Again
                </button>
            </div>
        </div>
    </div>
</div>
<script>
// Check for payment status and show appropriate modal
document.addEventListener('DOMContentLoaded', function() {
    // Check for successful payment
    const paymentSuccess = sessionStorage.getItem('paymentSuccess');
    const registrationMessage = sessionStorage.getItem('registrationMessage');
    
    if (paymentSuccess === 'true') {
        // Update modal message if custom message exists
        if (registrationMessage) {
            document.getElementById('modalMessage').textContent = registrationMessage;
        }
        
        // Show success modal
        const successModal = new bootstrap.Modal(document.getElementById('paymentSuccessModal'));
        successModal.show();
        
        // Clear the session storage
        sessionStorage.removeItem('paymentSuccess');
        sessionStorage.removeItem('registrationMessage');
    }
    
    // Check for failed payment
    const paymentFailed = sessionStorage.getItem('paymentFailed');
    if (paymentFailed === 'true') {
        // Show failed modal
        const failedModal = new bootstrap.Modal(document.getElementById('paymentFailedModal'));
        failedModal.show();
        
        // Clear the session storage
        sessionStorage.removeItem('paymentFailed');
    }
    
    // Auto-reload on failed modal close if still needed
    document.getElementById('paymentFailedModal').addEventListener('hidden.bs.modal', function () {
        // Optional: You can add auto-reload here if needed
        // location.reload();
    });
});
</script>

@if(session('donation_success') || session()->has('donation_success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const donationMessage = "{{ session('donation_message', 'Thank you for your donation!') }}";
    const donationAmount = "{{ session('donation_amount', '') }}";
    
    if (donationMessage) {
        showDonationSuccess(donationMessage, donationAmount);
    }
});
</script>
@endif

<script>
function showDonationSuccess(message, amount) {
    // Create popup element
    const popup = document.createElement('div');
    popup.className = 'alert alert-success alert-dismissible fade show position-fixed';
    popup.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    popup.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success me-2" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="mb-1">Donation Successful!</h6>
                <p class="mb-0">${message}</p>
                ${amount ? `<small class="text-muted">Amount: KES ${parseFloat(amount).toLocaleString()}</small>` : ''}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(popup);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (popup.parentNode) {
            popup.remove();
        }
    }, 5000);
}

// Check sessionStorage for donation success
document.addEventListener('DOMContentLoaded', function() {
    const donationSuccess = sessionStorage.getItem('donationSuccess');
    const donationMessage = sessionStorage.getItem('donationMessage');
    const donationAmount = sessionStorage.getItem('donationAmount');
    
    if (donationSuccess === 'true' && donationMessage) {
        showDonationSuccess(donationMessage, donationAmount);
        
        // Clear sessionStorage
        sessionStorage.removeItem('donationSuccess');
        sessionStorage.removeItem('donationMessage');
        sessionStorage.removeItem('donationAmount');
    }
});
</script>
<script>
// Image Modal Functionality
function openImageModal(imageSrc, title, description) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalCategory = document.getElementById('modalCategory');
    
    // Set modal content
    modalImage.src = imageSrc;
    modalImage.alt = title;
    modalTitle.textContent = title;
    modalDescription.textContent = description || 'No description available.';
    
    // Extract category from title or use default
    const category = 'Gallery'; // You can modify this based on your data structure
    modalCategory.textContent = category;
    
    // Store current image source for download
    modalImage.dataset.downloadSrc = imageSrc;
    modalImage.dataset.downloadName = title.toLowerCase().replace(/\s+/g, '-') + '.jpg';
    
    // Show modal
    modal.show();
}

// Download image function
function downloadImage() {
    const modalImage = document.getElementById('modalImage');
    const downloadLink = document.createElement('a');
    downloadLink.href = modalImage.dataset.downloadSrc;
    downloadLink.download = modalImage.dataset.downloadName;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Keyboard navigation support
document.addEventListener('keydown', function(event) {
    const imageModal = document.getElementById('imageModal');
    if (imageModal.classList.contains('show')) {
        if (event.key === 'Escape') {
            const modal = bootstrap.Modal.getInstance(imageModal);
            modal.hide();
        }
    }
});

// Close modal when clicking on the dark background
document.getElementById('imageModal').addEventListener('click', function(event) {
    if (event.target === this) {
        const modal = bootstrap.Modal.getInstance(this);
        modal.hide();
    }
});
</script>
  </body>
</html>