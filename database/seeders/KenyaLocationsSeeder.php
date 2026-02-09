<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KenyaLocationsSeeder extends Seeder
{
    public function run(): void
    {
        $counties = [
            'Mombasa' => [
                'Changamwe' => ['Port Reitz', 'Kipevu', 'Airport', 'Changamwe', 'Chaani'],
                'Jomvu' => ['Jomvu Kuu', 'Miritini', 'Mikindani'],
                'Kisauni' => ['Mjambere', 'Junda', 'Bamburi', 'Mwakirunge', 'Mtopanga', 'Magogoni'],
                'Nyali' => ['Frere Town', 'Ziwa La Ng\'ombe', 'Mkomani', 'Kongowea', 'Kadzandani'],
                'Likoni' => ['Mtongwe', 'Shika Adabu', 'Bofu', 'Likoni', 'Timbwani'],
                'Mvita' => ['Mji Wa Kale/Makadara', 'Tudor', 'Tononoka', 'Shimanzi/Ganjoni', 'Majengo']
            ],
            'Kwale' => [
                'Msambweni' => ['Gombato Bongwe', 'Ukunda', 'Kinondo', 'Mkongani'],
                'Lungalunga' => ['Pongwe/Kikoneni', 'Dzombo', 'Mwereni', 'Vanga'],
                'Matuga' => ['Tsimba Golini', 'Waa', 'Tiwi', 'Kubo South', 'Mkongani'],
                'Kinango' => ['Ndavaya', 'Puma', 'Kinango', 'Mackinnon Road', 'Chengoni/Samburu', 'Mwavumbo', 'Kasemeni']
            ],
            'Kilifi' => [
                'Kilifi North' => ['Tezo', 'Srere', 'Junju', 'Mwarakaya', 'Shimo La Tewa'],
                'Kilifi South' => ['Matsangoni', 'Mnarani', 'Junju', 'Mwarakaya', 'Shimo La Tewa'],
                'Kaloleni' => ['Mavueni', 'Mwawesa', 'Ruruma', 'Kambe/Ribe', 'Rabai/Kisurutini'],
                'Rabai' => ['Ruruma', 'Kambe/Ribe', 'Rabai/Kisurutini', 'Mwawesa'],
                'Ganze' => ['Ganze', 'Bamba', 'Jaribuni', 'Sokoke'],
                'Malindi' => ['Malindi Town', 'Shella', 'Ganda', 'Madunguni'],
                'Magarini' => ['Magarini', 'Gongoni', 'Adu', 'Garashi', 'Sabaki']
            ],
            'Tana River' => [
                'Garsen' => ['Kipini East', 'Garsen South', 'Kipini West', 'Garsen Central', 'Garsen West', 'Garsen North'],
                'Galole' => ['Kinakomba', 'Mikinduni', 'Chewani', 'Wayu'],
                'Bura' => ['Chewele', 'Hirimani', 'Bangale', 'Sala', 'Madogo']
            ],
            'Lamu' => [
                'Lamu East' => ['Faza', 'Kiunga', 'Basuba'],
                'Lamu West' => ['Shella', 'Mkomani', 'Hongwe', 'Bahari', 'Mkumbi']
            ],
            'Taita Taveta' => [
                'Taveta' => ['Chala', 'Mahoo', 'Bomeni', 'Kitobo', 'Taveta'],
                'Wundanyi' => ['Wundanyi/Mbale', 'Werugha', 'Wumingu/Kishushe', 'Mwanda/Mgange'],
                'Mwatate' => ['Ronge', 'Mwatate', 'Bura', 'Chawia', 'Wusi/Kishamba'],
                'Voi' => ['Mbololo', 'Sagalla', 'Kaloleni', 'Marungu', 'Kasigau']
            ],
            'Garissa' => [
                'Garissa Township' => ['Garissa', 'Iftin', 'Township'],
                'Balambala' => ['Balambala', 'Saka', 'Jara Jara'],
                'Lagdera' => ['Bura', 'Dekaharia', 'Jarajila', 'Fafi', 'Nanighi'],
                'Dadaab' => ['Dertu', 'Dadaab', 'Labasigale', 'Damajale', 'Liboi'],
                'Fafi' => ['Bura', 'Fafi', 'Nanighi'],
                'Ijara' => ['Masalani', 'Ijara', 'Sangailu', 'Korisa']
            ],
            'Wajir' => [
                'Wajir North' => ['Buna', 'Wajir Bor', 'Gurar', 'Batalu', 'Dambas'],
                'Wajir East' => ['Wagberi', 'Township', 'Barwago', 'Khorof/Harar'],
                'Wajir West' => ['Griftu', 'Kutulo', 'Wayama', 'Sarman'],
                'Wajir South' => ['Arbajahan', 'Hadado/Athibohol', 'Adamasajide', 'Ganyure/Wagalla'],
                'Eldas' => ['Eldas', 'Della', 'Lakoley South/Basir'],
                'Tarbaj' => ['Tarbaj', 'Wargadud', 'Benane']
            ],
            'Mandera' => [
                'Mandera West' => ['Lagsure', 'Dandu', 'Wargadud'],
                'Mandera North' => ['Elwak South', 'Elwak North', 'Shimbir Fatuma'],
                'Mandera Central' => ['Ashabito', 'Guticha', 'Morothile', 'Rhamu'],
                'Mandera East' => ['Mandera North', 'Mandera East', 'Mandera South'],
                'Lafey' => ['Sala', 'Fino', 'Lafey', 'Waranqara'],
                'Banissa' => ['Banissa', 'Derkhale', 'Guba', 'Malkamari', 'Kiliwehiri']
            ],
            'Marsabit' => [
                'Moyale' => ['Butiye', 'Sololo', 'Heilu/Manyatta', 'Golbo', 'Moyale', 'Uran'],
                'North Horr' => ['North Horr', 'Dukana', 'Maikona', 'Turbi'],
                'Saku' => ['Sagante/Jaldesa', 'Karare', 'Marsabit Central'],
                'Laisamis' => ['Laisamis', 'Kargi/South Horr', 'Korr/Ngurunit', 'Logologo']
            ],
            'Isiolo' => [
                'Isiolo North' => ['Wabera', 'Bulapesa', 'Burat', 'Oldonyiro', 'Chari'],
                'Isiolo South' => ['Cherab', 'Charri', 'Kina', 'Garba Tulla']
            ],
            'Meru' => [
                'North Imenti' => ['Antuambui', 'Ntima East', 'Ntima West', 'Nyaki West', 'Nyaki East'],
                'Buuri' => ['Timau', 'Kisima', 'Kiirua/Naari', 'Ruiri/Rwarera'],
                'Central Imenti' => ['Mwanganthia', 'Abothuguchi Central', 'Abothuguchi West', 'Kiagu'],
                'South Imenti' => ['Mitunguu', 'Igoji East', 'Igoji West', 'Abogeta East', 'Abogeta West'],
                'Igembe South' => ['Maua', 'Kiegoi/Antubochiu', 'Athiru Gaiti', 'Akachiu'],
                'Igembe Central' => ['Kangeta', 'Athiru Ruujine', 'Mwanganthia', 'Igembe East'],
                'Igembe North' => ['Kibirichia', 'Athiru', 'Muthara', 'Kianjai'],
                'Tigania West' => ['Mbeu', 'Thangatha', 'Mikinduri', 'Kiguchwa'],
                'Tigania East' => ['Muthara', 'Karama', 'Kianjai', 'Nkuene']
            ],
            'Tharaka Nithi' => [
                'Tharaka' => ['Gatunga', 'Mukothima', 'Nkondi', 'Chiakariga', 'Marimanti'],
                'Chuka' => ['Mariani', 'Karingani', 'Magumoni', 'Mugwe', 'Igambang\'ombe'],
                'Maara' => ['Mitheru', 'Muthambi', 'Mwimbi', 'Ganga', 'Chogoria']
            ],
            'Embu' => [
                'Manyatta' => ['Ruguru/Ngandori', 'Kithimu', 'Nginda', 'Mbeti South', 'Kirimari'],
                'Runyenjes' => ['Gaturi South', 'Gaturi North', 'Kagaari South', 'Kagaari North', 'Kyeni North', 'Kyeni South'],
                'Mbeere North' => ['Mwea', 'Makima', 'Mbeti North', 'Kiambere', 'Nthawa'],
                'Mbeere South' => ['Mavuria', 'Kiambere', 'Nthawa', 'Mwea', 'Makima']
            ],
            'Kitui' => [
                'Mwingi North' => ['Ngomeni', 'Kyuso', 'Mumoni', 'Tseikuru', 'Tharaka'],
                'Mwingi West' => ['Kyome/Thaana', 'Nguutani', 'Migwani', 'Kiomo/Kyethani'],
                'Mwingi Central' => ['Nguni', 'Nuu', 'Mui', 'Waita'],
                'Kitui West' => ['Mutonguni', 'Kauwi', 'Matinyani', 'Kwa Mutonga/Kithumula'],
                'Kitui Rural' => ['Kisasi', 'Mbitini', 'Kwavonza/Yatta', 'Kanyangi'],
                'Kitui Central' => ['Miambani', 'Township', 'Kyangwithya West', 'Kyangwithya East'],
                'Kitui East' => ['Voo/Kyamatu', 'Endau/Malalani', 'Mutito/Kaliku'],
                'Kitui South' => ['Ikanga/Kyatune', 'Mutomo', 'Mutha', 'Ikutha', 'Athi']
            ],
            'Machakos' => [
                'Mavoko' => ['Athi River', 'Kinanie', 'Muthwani', 'Syokimau/Mulolongo'],
                'Machakos Town' => ['Kalama', 'Mua', 'Mutituni', 'Machakos Central', 'Mumbuni North'],
                'Mwala' => ['Mbiuni', 'Makutano/Mwala', 'Masii', 'Muthetheni', 'Wamunyu'],
                'Yatta' => ['Kithimani', 'Ikombe', 'Katangi', 'Kangundo North', 'Kangundo South'],
                'Kangundo' => ['Kangundo North', 'Kangundo South', 'Kangundo Central', 'Kangundo East'],
                'Matungulu' => ['Tala', 'Matungulu North', 'Matungulu East', 'Matungulu West'],
                'Kathiani' => ['Mitaboni', 'Kathiani Central', 'Upper Kaewa/Iveti', 'Lower Kaewa/Kaani']
            ],
            'Makueni' => [
                'Mbooni' => ['Tulimani', 'Mbooni', 'Kithungo/Kitundu', 'Kiteta/Kisau', 'Waita'],
                'Kilome' => ['Kikumbulyu North', 'Kikumbulyu South', 'Nguu/Masumba', 'Kee', 'Kathonzweni'],
                'Kaiti' => ['Kithuki', 'Muvau/Kikuumini', 'Makuuni', 'Wote', 'Kyuani/Kithoni'],
                'Makueni' => ['Muvuti/Kiima-Kimwe', 'Kathonzweni', 'Nzaui/Kalamba', 'Mbitini'],
                'Kibwezi West' => ['Kikumbulyu North', 'Kikumbulyu South', 'Nguu/Masumba', 'Kee'],
                'Kibwezi East' => ['Masongaleni', 'Mtito Andei', 'Thange', 'Ivingoni/Nzambani']
            ],
            'Nyandarua' => [
                'Kinangop' => ['Engineer', 'Gathara', 'North Kinangop', 'Murungaru', 'Njabini'],
                'Kipipiri' => ['Geta', 'Githioro', 'Kipipiri', 'Wanjohi'],
                'Ol Kalou' => ['Ol Kalou', 'Kinangop Central', 'Kaimbaga', 'Rurii'],
                'Ol Jorok' => ['Gathanji', 'Gatimu', 'Weru', 'Charagita'],
                'Ndaragwa' => ['Leshau/Pondo', 'Kiriita', 'Central', 'Shamata']
            ],
            'Nyeri' => [
                'Tetu' => ['Dedan Kimanthi', 'Wamagana', 'Aguthi-Gaaki'],
                'Kieni' => ['Mweiga', 'Naromoru Kiamathaga', 'Mwiyogo/Endarasha', 'Mugunda', 'Gatarakwa', 'Thegu River'],
                'Mathira' => ['Ruguru', 'Magutu', 'Iriani', 'Konyu', 'Kirimukuyu', 'Karatina Town'],
                'Othaya' => ['Chinga', 'Karima', 'Mahiga', 'Iriaini', 'Konyu'],
                'Mukurweini' => ['Mukurweini Central', 'Mukurweini West', 'Gikondi', 'Rugi', 'Muhito'],
                'Nyeri Town' => ['Kamakwa/Mukaro', 'Rware', 'Gatitu/Muruguru', 'Ruring\'u', 'Kiganjo/Mathari']
            ],
            'Kirinyaga' => [
                'Mwea' => ['Thiba', 'Kangai', 'Muthithi', 'Wamumu', 'Nyangati', 'Murinduko'],
                'Gichugu' => ['Ngariama', 'Kanyekini', 'Kerugoya', 'Inoi'],
                'Ndia' => ['Mukure', 'Kiine', 'Kariti', 'Mutira'],
                'Kirinyaga Central' => ['Kanyekini', 'Kerugoya', 'Inoi', 'Mutira']
            ],
            'Murang\'a' => [
                'Kangema' => ['Kangema', 'Kigumo', 'Murarandia', 'Kiharu'],
                'Mathioya' => ['Gitugi', 'Kiru', 'Kamacharia', 'Wandumbi'],
                'Kiharu' => ['Mwihoko', 'Mugoiri', 'Mbiri', 'Township', 'Murarandia'],
                'Kigumo' => ['Kinyona', 'Kigumo', 'Kangari', 'Kambiti'],
                'Maragwa' => ['Kambere', 'Kiharu', 'Gatanga', 'Kigumo'],
                'Kandara' => ['Gatanga', 'Kandara', 'Kakuzi', 'Mitunguu'],
                'Gatanga' => ['Ithanga', 'Kakuzi', 'Mitunguu', 'Ruiru']
            ],
            'Kiambu' => [
                'Gatundu South' => ['Kiamwangi', 'Kiganjo', 'Ndarugu', 'Ngenda'],
                'Gatundu North' => ['Gatundu North', 'Gatundu South', 'Gituamba', 'Kiamwangi'],
                'Juja' => ['Murera', 'Theta', 'Juja', 'Witeithie', 'Kalimoni'],
                'Thika Town' => ['Township', 'Kamenu', 'Hospital', 'Gatuanyaga', 'Ngoliba'],
                'Ruiru' => ['Githurai', 'Kahawa Wendani', 'Ruiru', 'Kiuu', 'Mwiki'],
                'Githunguri' => ['Githunguri', 'Komothai', 'Ngewa', 'Kiamwangi'],
                'Kiambu' => ['Cianda', 'Karuri', 'Ndenderu', 'Muchatha', 'Kihara'],
                'Kiambaa' => ['Cianda', 'Karuri', 'Ndenderu', 'Muchatha'],
                'Kabete' => ['Kabete', 'Uthiru', 'Muguga', 'Nyathuna', 'Karai'],
                'Kikuyu' => ['Kikuyu', 'Karai', 'Nachu', 'Sigona', 'Kinoo'],
                'Limuru' => ['Limuru Central', 'Ndeiya', 'Limuru East', 'Ngecha'],
                'Lari' => ['Kireita', 'Kijabe', 'Nyanduma', 'Kamburu', 'Lari/Kirenga']
            ],
            'Turkana' => [
                'Turkana North' => ['Kakuma', 'Lapur', 'Kaeris', 'Songot', 'Kalobeyei'],
                'Turkana West' => ['Lokichogio', 'Nanaam', 'Kakuma', 'Letea', 'Songot'],
                'Turkana Central' => ['Lodwar Township', 'Kanamkemer', 'Kerio Delta', 'Kangatotha'],
                'Loima' => ['Kotaruk/Lobei', 'Turkwel', 'Loima', 'Lokiriama/Lorengippi'],
                'Turkana South' => ['Kaputir', 'Katilu', 'Lobokat', 'Kalapata'],
                'Turkana East' => ['Kapedo/Napeitom', 'Katilia', 'Lokori/Kochodin']
            ],
            'West Pokot' => [
                'Kapenguria' => ['Riwo', 'Kapenguria', 'Mnagei', 'Siyoi', 'Endugh'],
                'Sigor' => ['Suam', 'Kodich', 'Kapchok', 'Riwo', 'Kasei'],
                'Kacheliba' => ['Kacheliba', 'Kodich', 'Kasei', 'Kaporon'],
                'Pokot South' => ['Chepareria', 'Batei', 'Lelan', 'Tapach']
            ],
            'Samburu' => [
                'Samburu West' => ['Suguta Marmar', 'Maralal', 'Loosuk', 'Porro'],
                'Samburu North' => ['Wamba West', 'Wamba East', 'Wamba North', 'Waso'],
                'Samburu East' => ['Waso', 'Ndoto', 'Nyiro', 'Angata Nanyokie']
            ],
            'Trans Nzoia' => [
                'Kwanza' => ['Bidii', 'Chepchoina', 'Endebess', 'Matumbei'],
                'Endebess' => ['Endebess', 'Matumbei', 'Kinyoro', 'Chepchoina'],
                'Saboti' => ['Machewa', 'Kinyoro', 'Rwambwa', 'Saboti'],
                'Kiminini' => ['Waitaluk', 'Sirende', 'Hospital', 'Sikhendu', 'Nabiswa'],
                'Cherangany' => ['Chepsiro', 'Sitatunga', 'Kapomboi', 'Kinyoro']
            ],
            'Uasin Gishu' => [
                'Soy' => ['Kipsomba', 'Soy', 'Kuinet/Kapsuswa', 'Segero/Barsombe', 'Ziwa'],
                'Turbo' => ['Huruma', 'Kamagut', 'Kapsaos', 'Tapsagoi'],
                'Moiben' => ['Tembelio', 'Sergoit', 'Karuna/Meibeki', 'Kimumu'],
                'Ainabkoi' => ['Kapsoya', 'Kaptagat', 'Ainabkoi/Olare'],
                'Kapseret' => ['Kipkenyo', 'Kapseret', 'Ngeria', 'Megun', 'Langas'],
                'Kesses' => ['Racecourse', 'Cheptiret/Kipchamo', 'Tulwet/Chuiyat', 'Tarakwa']
            ],
            'Elgeyo Marakwet' => [
                'Marakwet East' => ['Kapyego', 'Sambirir', 'Endo', 'Embobut / Embulot'],
                'Marakwet West' => ['Lelan', 'Sengwer', 'Cherangany/Chebororwa', 'Moiben/Kuserwo'],
                'Keiyo North' => ['Tambach', 'Kaptarakwa', 'Chepkorio', 'Soy North'],
                'Keiyo South' => ['Metkei', 'Soi', 'Kapsowar', 'Lembus']
            ],
            'Nandi' => [
                'Tinderet' => ['Songhor/Soba', 'Tindiret', 'Chemelil/Chemase', 'Kapsimotwo'],
                'Aldai' => ['Kabiyet', 'Ndalat', 'Kabisaga', 'Kaptumo Kaboi'],
                'Nandi Hills' => ['Chepkunyuk', 'Ol\'lessos', 'Kapchorua'],
                'Chesumei' => ['Kapsabet', 'Kilibwoni', 'Chepkumia', 'Kapkangani'],
                'Emgwen' => ['Kaptel/Kamoiywo', 'Kiptuya', 'Chepterwai', 'Kapsabet'],
                'Mosop' => ['Kabisaga', 'Chepterwai', 'Kipkaren', 'Kurgung/Surungai']
            ],
            'Baringo' => [
                'Tiaty' => ['Kolowa', 'Ribkwo', 'Silale', 'Loiyamorock', 'Tangulbei/Korossi'],
                'Baringo North' => ['Kabartonjo', 'Saimo/Kipsaraman', 'Saimo/Soi', 'Bartabwa'],
                'Baringo Central' => ['Kabarnet', 'Sacho', 'Tenges', 'Ewalel Chapchap'],
                'Baringo South' => ['Marigat', 'Ilchamus', 'Mochongoi', 'Mukutani'],
                'Mogotio' => ['Mogotio', 'Emining', 'Kisanana', 'Lembus'],
                'Eldama Ravine' => ['Lembus Kwen', 'Ravine', 'Lembus', 'Mumberes/Maji Mazuri']
            ],
            'Laikipia' => [
                'Laikipia West' => ['Ol Moran', 'Rumuruti Township', 'Githiga', 'Marmanet'],
                'Laikipia East' => ['Ngobit', 'Tigithi', 'Thingithu', 'Nanyuki', 'Umande'],
                'Laikipia North' => ['Sosian', 'Segera', 'Mugogodo West', 'Mugogodo East']
            ],
            'Nakuru' => [
                'Molo' => ['Molo', 'Elburgon', 'Turi', 'Mariashoni'],
                'Njoro' => ['Njoro', 'Mauche', 'Kihingo', 'Nessuit', 'Lare'],
                'Naivasha' => ['Biashara', 'Hells Gate', 'Lake View', 'Maiella', 'Maai Mahiu'],
                'Gilgil' => ['Gilgil', 'Elementaita', 'Mbaruk/Eburu', 'Malewa West'],
                'Kuresoi South' => ['Keringet', 'Kiptororo', 'Nyota', 'Sirikwa'],
                'Kuresoi North' => ['Dundori', 'Kabatini', 'Kiamaina', 'Lanet/Umoja'],
                'Subukia' => ['Subukia', 'Waseges', 'Kabazi'],
                'Rongai' => ['Solai', 'Visoi', 'Mosop', 'Soin'],
                'Bahati' => ['Dundori', 'Kabatini', 'Kiamaina', 'Lanet/Umoja', 'Bahati'],
                'Nakuru Town West' => ['Barut', 'London', 'Kaptembwo', 'Kapkures'],
                'Nakuru Town East' => ['Biashara', 'Kivumbini', 'Flamingo', 'Menengai']
            ],
            'Narok' => [
                'Kilgoris' => ['Kilgoris Central', 'Keyian', 'Angata Barikoi', 'Shankoe'],
                'Emurua Dikirr' => ['Emurua Dikirr', 'Mogondo', 'Kapsasian'],
                'Narok North' => ['Ololulung\'a', 'Melili', 'Mau Narok', 'Majimoto/Naroosura'],
                'Narok East' => ['Nkareta East', 'Nkareta West', 'Entonet/Lenkisim'],
                'Narok South' => ['Naroosura', 'Ololulung\'a', 'Melelo', 'Loita'],
                'Narok West' => ['Siana', 'Ilkerin', 'Ololmasani', 'Mara']
            ],
            'Kajiado' => [
                'Kajiado North' => ['Olkeri', 'Ongata Rongai', 'Nkaimurunya', 'Oloolua', 'Ngong'],
                'Kajiado Central' => ['Purko', 'Ildamat', 'Dalalekutuk', 'Matapato North', 'Matapato South'],
                'Kajiado East' => ['Kaputiei North', 'Kitengela', 'Oloosirkon/Sholinke', 'Kenyaawa-Poka'],
                'Kajiado West' => ['Kajiado Central', 'Elangata Wuas', 'Mashuuru', 'Ewuaso Oonkidong'],
                'Kajiado South' => ['Rombo', 'Kimana', 'Loitokitok', 'Entonet/Lenkisim']
            ],
            'Kericho' => [
                'Kipkelion West' => ['Kipkelion', 'Kunyak', 'Kapsaos', 'Chilchila'],
                'Kipkelion East' => ['Londiani', 'Kedowa/Kimugul', 'Chepseon', 'Tendeno/Sorget'],
                'Ainamoi' => ['Ainamoi', 'Kapsoit', 'Kabinet', 'Kipchebor', 'Kipchimchim'],
                'Bureti' => ['Cheplanget', 'Kapsuser', 'Litein', 'Cheborge'],
                'Belgut' => ['Belgut', 'Sosiot', 'Waldai', 'Kapkugerwet'],
                'Sigowet–Soin' => ['Sigowet', 'Soin', 'Kaplelartet', 'Soliat']
            ],
            'Bomet' => [
                'Bomet Central' => ['Silibwet Township', 'Ndaraweta', 'Singorwet', 'Chesoen'],
                'Bomet East' => ['Merigi', 'Kembu', 'Longisa', 'Kipreres'],
                'Chepalungu' => ['Chepalungu', 'Kong"asis', 'Noigam', 'Sigor'],
                'Sotik' => ['Sotik', 'Ndanai/Abosi', 'Chemagel', 'Kipsonoi'],
                'Konoin' => ['Mogogosiek', 'Boito', 'Embomos', 'Chemaner']
            ],
            'Kakamega' => [
                'Likuyani' => ['Likuyani', 'Sango', 'Kongoni', 'Nzoia'],
                'Malava' => ['Malava', 'Lugari', 'Mautuma', 'Chegulo'],
                'Lurambi' => ['Butali/Chegulo', 'Mwichuni', 'Lurambi', 'Marenyo'],
                'Navakholo' => ['Navakholo', 'Bunyala West', 'Bunyala East', 'Bumula'],
                'Mumias West' => ['Mumias Central', 'Mumias North', 'Etenje', 'Musanda'],
                'Mumias East' => ['Mumias East', 'Lusheya/Lubinu', 'Malaha/Isongo/Makunga'],
                'Matungu' => ['Koyonzo', 'Kholera', 'Khalaba', 'Mayoni'],
                'Butere' => ['Butere', 'Maranthi', 'Mwichiru', 'Bumwani'],
                'Khwisero' => ['Khwisero', 'Shianda', 'Emabungo', 'Shikoti'],
                'Shinyalu' => ['Shinyalu', 'Murhanda', 'Gangu', 'Musikoma'],
                'Ikolomani' => ['Ikolomani', 'Idakho Central', 'Idakho North', 'Idakho South']
            ],
            'Vihiga' => [
                'Vihiga' => ['Wodanga', 'Busali', 'Chavakali', 'Lyaduywa/Izava'],
                'Sabatia' => ['Sabatia', 'Chavakali', 'North Maragoli', 'Wodanga'],
                'Hamisi' => ['Hamisi', 'Tambua', 'Jepkoyai', 'Banja'],
                'Luanda' => ['Luanda Township', 'Wemilabi', 'Mwibona', 'Luanda South'],
                'Emuhaya' => ['Emuhaya', 'North East Bunyore', 'Central Bunyore']
            ],
            'Bungoma' => [
                'Mt. Elgon' => ['Kapsokwony', 'Kaptama', 'Elgon', 'Kapsokwony'],
                'Sirisia' => ['Sirisia', 'Kabuchai', 'Bwake/Luuya', 'Cheptais'],
                'Kabuchai' => ['Kabuchai', 'Naitiri', 'Bokoli', 'Ndalu'],
                'Bumula' => ['Bumula', 'Khasoko', 'Kabula', 'Kimaeti'],
                'Kanduyi' => ['Kanduyi', 'Marakaru/Tuuti', 'West Sang"alo', 'Mihuu'],
                'Webuye East' => ['Bokoli', 'Mihuu', 'Misikhu', 'Sitikho'],
                'Webuye West' => ['Webuye West', 'Bokoli', 'Mihuu', 'Misikhu'],
                'Kimilili' => ['Kimilili', 'Kibingei', 'Maeni', 'Kamukuywa'],
                'Tongaren' => ['Tongaren', 'Soysambu/Mitua', 'Nabiswa', 'Mbakalo']
            ],
            'Busia' => [
                'Teso North' => ['Malaba Central', 'Malaba North', "Ang'urai South", "Ang'urai North"],
                'Teso South' => ['Ang"urai', 'Amukura', 'Chakol South', 'Chakol North'],
                'Nambale' => ['Nambale Township', 'Bukhayo North/Walatsi', 'Bukhayo Central', 'Bukhayo East'],
                'Matayos' => ['Bunyala Central', 'Bunyala North', 'Bunyala West', 'Bunyala South'],
                'Butula' => ['Butula', 'Marachi West', 'Marachi Central', 'Marachi East'],
                'Funyula' => ['Funyula', 'Budalangi', 'Bunyala', 'Bukhayo'],
                'Budalangi' => ['Budalangi', 'Bunyala', 'Bukhayo', 'Port Victoria']
            ],
            'Siaya' => [
                'Ugenya' => ['Ukwala', 'Ugunja', 'Sidindi', 'Sigomere'],
                'Ugunja' => ['Ugunja', 'Sidindi', 'Sigomere', 'Ukwala'],
                'Alego Usonga' => ['Alego', 'Usonga', 'North Alego', 'South Alego'],
                'Gem' => ['Gem', 'Wagai', 'Yala', 'Karemo'],
                'Bondo' => ['Bondo', 'Nyang"oma', 'Uyawi', 'West Gem'],
                'Rarieda' => ['Rarieda', 'Asembo', 'West Uyoma', 'East Uyoma']
            ],
            'Kisumu' => [
                'Kisumu East' => ['Kajulu', 'Kolwa East', 'Manyatta "B"', 'Nyalenda "A"'],
                'Kisumu West' => ['Kisumu West', 'Central Seme', 'South West Kisumu', 'North West Kisumu'],
                'Kisumu Central' => ['Kisumu Central', 'Kajulu', 'Kanyakwar', 'Railways'],
                'Seme' => ['Seme', 'East Seme', 'North Seme', 'West Seme'],
                'Nyando' => ['Awasi/Onjiko', 'Ahero', 'Kobura', 'Ombeyi'],
                'Muhoroni' => ['Muhoroni/Koru', 'South West Nyakach', 'North Nyakach', 'Central Nyakach'],
                'Nyakach' => ['West Nyakach', 'South West Nyakach', 'North Nyakach', 'Central Nyakach']
            ],
            'Homa Bay' => [
                'Kasipul' => ['Kasipul', 'Kabondo', 'Kanyadoto', 'West Kasipul'],
                'Kabondo Kasipul' => ['Kabondo', 'Kanyadoto', 'Kasipul', 'West Kasipul'],
                'Karachuonyo' => ['Karachuonyo', 'Kanyaluo', 'Kibiri', 'Wang"chieng"'],
                'Rangwe' => ['Rangwe', 'Kanyikela', 'Kochia', 'Homa Bay Central'],
                'Homa Bay Town' => ['Homa Bay Central', 'Homa Bay Arujo', 'Homa Bay West', 'Homa Bay East'],
                'Ndhiwa' => ['Ndhiwa', 'Kanyamwa Kologi', 'Kanyamwa Kosewe', 'North Kabuoch'],
                'Suba North' => ['Suba North', 'Mfangano', 'Rusinga', 'Gwassi'],
                'Suba South' => ['Suba South', 'Mfangano', 'Rusinga', 'Gwassi']
            ],
            'Migori' => [
                'Rongo' => ['Rongo', 'North Kamagambo', 'Central Kamagambo', 'South Kamagambo'],
                'Awendo' => ['Awendo', 'North Sakwa', 'South Sakwa', 'West Sakwa'],
                'Suna East' => ['Suna East', 'Kakrao', 'Kwa', 'Central Sakwa'],
                'Suna West' => ['Suna West', 'North Sakwa', 'South Sakwa', 'West Sakwa'],
                'Uriri' => ['Uriri', 'North Kadem', 'Macalder/Kanyarwanda', 'Kaler'],
                'Nyatike' => ['Nyatike', 'Kanyarwanda', 'Kaler', 'Got Kachola'],
                'Kuria West' => ['Kegonga', 'Kehancha', 'Kegonga', 'Getong"anya'],
                'Kuria East' => ['Kegonga', 'Kehancha', 'Kegonga', 'Getong"anya']
            ],
            'Kisii' => [
                'Bonchari' => ['Bomariba', 'Bogiakumu', 'Bomorenda', 'Riana'],
                'South Mugirango' => ['Bomorenda', 'Bomwagamo', 'Bokeira', 'Magenche'],
                'Bomachoge Borabu' => ['Bomachoge Borabu', 'Bobasi Boitangare', 'Bobasi Chache', 'Bomachoge Chache'],
                'Bobasi' => ['Bobasi Boitangare', 'Bobasi Chache', 'Bomachoge Borabu', 'Bomachoge Chache'],
                'Bomachoge Chache' => ['Bomachoge Chache', 'Bobasi Boitangare', 'Bobasi Chache', 'Bomachoge Borabu'],
                'Nyaribari Masaba' => ['Nyaribari Masaba', 'Nyaribari Chache', 'Bobasi', 'Bomachoge'],
                'Nyaribari Chache' => ['Nyaribari Chache', 'Nyaribari Masaba', 'Bobasi', 'Bomachoge'],
                'Kitutu Chache North' => ['Kitutu Chache North', 'Kitutu Chache South', 'Nyaribari Masaba', 'Nyaribari Chache'],
                'Kitutu Chache South' => ['Kitutu Chache South', 'Kitutu Chache North', 'Nyaribari Masaba', 'Nyaribari Chache']
            ],
            'Nyamira' => [
                'Kitutu Masaba' => ['Kitutu Masaba', 'North Mugirango', 'West Mugirango', 'Borabu'],
                'West Mugirango' => ['West Mugirango', 'North Mugirango', 'Borabu', 'Kitutu Masaba'],
                'North Mugirango' => ['North Mugirango', 'West Mugirango', 'Borabu', 'Kitutu Masaba'],
                'Borabu' => ['Borabu', 'North Mugirango', 'West Mugirango', 'Kitutu Masaba']
            ],
            'Nairobi' => [
                'Westlands' => ['Kitisuru', 'Parklands/Highridge', 'Karura', 'Kangemi', 'Mountain View'],
                'Dagoretti North' => ['Kilimani', 'Kawangware', 'Gatina', 'Kileleshwa', 'Kabiro'],
                'Dagoretti South' => ['Mutu-ini', 'Ngando', 'Riruta', 'Uthiru/Ruthimitu', 'Waithaka'],
                'Langata' => ['Karen', 'Nairobi West', 'Mugumo-ini', 'South C', 'Nyayo Highrise'],
                'Kibra' => ['Laini Saba', 'Lindi', 'Makina', 'Woodley/Kenyatta Golf Course', 'Sarang"ombe'],
                'Roysambu' => ['Githurai', 'Kahawa West', 'Zimmerman', 'Roysambu', 'Kahawa'],
                'Kasarani' => ['Clay City', 'Mwiki', 'Kasarani', 'Njiru', 'Ruai'],
                'Ruaraka' => ['Baba Dogo', 'Utalii', 'Mathare North', 'Lucky Summer', 'Korogocho'],
                'Embakasi South' => ['Imara Daima', 'Kwa Njenga', 'Kwa Reuben', 'Pipeline', 'Kware'],
                'Embakasi North' => ['Kariobangi North', 'Dandora Area I', 'Dandora Area II', 'Dandora Area III', 'Dandora Area IV'],
                'Embakasi Central' => ['Kayole North', 'Kayole Central', 'Kayole South', 'Komarock', 'Matopeni/Spring Valley'],
                'Embakasi East' => ['Upper Savanna', 'Lower Savanna', 'Embakasi', 'Utawala', 'Mihang"o'],
                'Embakasi West' => ['Umoja I', 'Umoja II', 'Mowlem', 'Kariobangi South'],
                'Makadara' => ['Maringo/Hamza', 'Viwandani', 'Harambee', 'Makongeni', 'Mbotela'],
                'Kamukunji' => ['Pumwani', 'Eastleigh North', 'Eastleigh South', 'Airbase', 'California'],
                'Starehe' => ['Nairobi Central', 'Ngara', 'Pangani', 'Ziwani/Kariokor', 'Landimawe', 'Nairobi South'],
                'Mathare' => ['Hospital', 'Mabatini', 'Huruma', 'Ngei', 'Mlango Kubwa', 'Kiamaiko']
            ]
        ];

        foreach ($counties as $county => $constituencies) {
            foreach ($constituencies as $constituency => $wards) {
                foreach ($wards as $ward) {
                    DB::table('kenya_locations')->insert([
                        'county' => $county,
                        'constituency' => $constituency,
                        'ward' => $ward,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}