<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Package;
use App\Models\PackageItinerary;
use App\Models\PackagePlan;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@overlander.test'],
            ['name' => 'Admin Overlander', 'password' => bcrypt(env('DEMO_ADMIN_PASSWORD', 'password')), 'is_admin' => true, 'email_verified_at' => now()]
        );

        $member = User::firstOrCreate(
            ['email' => 'member@overlander.test'],
            ['name' => 'Traveler Demo', 'password' => bcrypt('password'), 'phone' => '081234567890', 'email_verified_at' => now()]
        );

        $catByName = fn (string $name) => Category::where('name', $name)->first();

        // ---------- Destinations ----------
        $ijenActivities = require database_path('data/ijen_activities.php');
        $activityTitlesId = require database_path('data/activity_titles_id.php');

        $destinationsData = [
            [
                'name' => 'Mount Bromo', 'location' => 'East Java, ID',
                'lat' => -7.9425, 'lng' => 112.9530,
                'description_en' => "Watching the sun rise over Bromo's crater rim is one of those bucket-list moments people talk about for years — and for good reason. This still-active volcano sits in the middle of a vast, otherworldly sand sea, with jeeps kicking up trails of dust as the sky turns orange, pink, and gold behind Semeru in the distance. It's cold, it's early, and it's absolutely worth losing sleep for.",
                'description_id' => 'Nonton matahari terbit di bibir kawah Bromo itu salah satu momen yang bakal kamu ceritain terus ke orang bertahun-tahun ke depan — dan emang pantas. Gunung berapi yang masih aktif ini berdiri di tengah lautan pasir yang luas dan kayak dari planet lain, dengan jip-jip yang ngebut ninggalin jejak debu pas langit berubah oranye, pink, terus keemasan dengan Semeru di kejauhan. Dingin, harus bangun pagi banget, tapi worth it banget buat rela kurang tidur.',
                'what_to_do_en' => "Hop in a 4x4 jeep before dawn and race to Penanjakan viewpoint for the sunrise show, then head down into the sand sea itself — you can hike or hop on a horse up to the crater rim, where sulfur smoke still curls out of the ground. Don't skip a quick stop at Teletubbies Hill on the way back, it's exactly as cute as it sounds.",
                'what_to_do_id' => 'Naik jip 4x4 sebelum subuh terus tancap gas ke Penanjakan buat nonton sunrise, habis itu turun ke lautan pasirnya sendiri — bisa jalan kaki atau naik kuda sampai ke bibir kawah, tempat asap belerang masih ngepul dari tanah. Jangan lupa mampir sebentar ke Bukit Teletubbies pas balik, bentuknya emang selucu itu.',
                'point_of_interest_en' => 'The Penanjakan sunrise point (arrive early, it gets packed), the still-smoking active crater you can peer right into, and the surreal green hills locals nicknamed after a certain kids\' show.',
                'point_of_interest_id' => 'Titik sunrise Penanjakan (dateng lebih pagi ya, rame banget), kawah aktif yang masih ngebul yang bisa kamu intip langsung, dan bukit-bukit hijau unik yang sama warga dijulukin sesuai nama acara anak-anak itu.',
                'nature' => 5, 'culture' => 2, 'heritage' => 2,
                'cats' => ['Nature', 'Mountain'],
                'photo' => 'https://images.unsplash.com/photo-1548430065-53c58a6582dd?w=1200',
                'cover_photo' => 'destinations/mount-bromo.jpg',
                'activities' => [
                    [
                        'title' => 'Jeep Sunrise Tour', 'type' => 'adventure',
                        'desc_en' => "Squeeze into a 4x4 with your travel buddies and let the driver do the hard work — navigating the dark, bumpy tracks up to Penanjakan while you save your energy for the view that's about to hit you.",
                        'desc_id' => 'Squeeze masuk ke jip 4x4 bareng temen seperjalanan, biarin drivernya yang kerja keras — nyusurin jalan gelap dan bergelombang menuju Penanjakan, sementara kamu tinggal nyimpen energi buat pemandangan yang bakal bikin kamu speechless.',
                    ],
                    [
                        'title' => 'Crater Trekking', 'type' => 'tracking',
                        'desc_en' => "A short, easy walk (or horseback ride if you're feeling fancy) across the sand sea straight up to the crater's edge, close enough to feel the warm sulfur breeze.",
                        'desc_id' => 'Jalan santai (atau naik kuda kalau mau lebih santai lagi) nyebrangin lautan pasir langsung ke bibir kawah, deket banget sampai kerasa hembusan angin belerang yang hangat.',
                    ],
                ],
            ],
            [
                'name' => 'Borobudur Temple', 'location' => 'Magelang, Central Java, ID',
                'active' => false,
                'lat' => -7.6079, 'lng' => 110.2038,
                'description_en' => "Borobudur isn't just old — it's the largest Buddhist temple on Earth, built over a thousand years ago by the Syailendra Dynasty and still standing as one of Indonesia's proudest landmarks. Walk its stone terraces at sunrise, when the mist is still clinging to the surrounding hills, and it's easy to feel like you've stepped into a different century.",
                'description_id' => 'Borobudur bukan cuma tua — ini candi Buddha terbesar di dunia, dibangun lebih dari seribu tahun lalu sama Dinasti Syailendra dan masih berdiri megah jadi salah satu ikon paling dibanggakan Indonesia. Jalan-jalan di teras batunya pas sunrise, waktu kabut masih nyelimutin bukit-bukit sekitar, rasanya kayak beneran pindah ke zaman lain.',
                'what_to_do_en' => "Catch the sunrise tour before the crowds roll in, then spend a couple of unhurried hours tracing the relief carvings that wind around every level of the temple. If you want the full picture, duck into the Karmawibhangga Museum nearby — it fills in a lot of the backstory the stones don't tell.",
                'what_to_do_id' => 'Ikutan sunrise tour sebelum rame pengunjung, terus habiskan waktu santai buat nyusurin relief yang ngelilingin tiap tingkat candi. Kalau mau tau cerita lengkapnya, mampir sebentar ke Museum Karmawibhangga di dekat situ — banyak cerita di balik batu yang gak keliatan cuma dari lihat reliefnya aja.',
                'point_of_interest_en' => "The world's longest continuous stretch of Buddhist relief carving, the temple's iconic central stupa, and that once-in-a-lifetime sunrise view over the surrounding jungle and volcanoes.",
                'point_of_interest_id' => 'Relief Buddha terpanjang dan paling utuh di dunia, stupa utama yang jadi ikon candi ini, dan pemandangan sunrise sekali seumur hidup di atas hutan dan gunung berapi sekitarnya.',
                'nature' => 2, 'culture' => 5, 'heritage' => 5,
                'cats' => ['Culture'],
                'photo' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1200',
                'cover_photo' => 'destinations/borobudur-temple.jpg',
                'activities' => [
                    [
                        'title' => 'Sunrise Tour', 'type' => 'tradition',
                        'desc_en' => "Climb up while it's still dark and claim your spot before the sun paints the whole temple gold — trust us, the early alarm is worth it.",
                        'desc_id' => 'Naik ke atas selagi masih gelap dan ambil tempat duduk terbaik sebelum matahari mewarnai seluruh candi jadi keemasan — percaya deh, bangun pagi-pagi itu worth it banget.',
                    ],
                    [
                        'title' => 'Heritage Walk', 'type' => 'tradition',
                        'desc_en' => "Follow a local guide as they point out stories hidden in the stone — from the Buddha's life to everyday scenes from a thousand years ago.",
                        'desc_id' => 'Ikutin pemandu lokal yang nunjukin cerita-cerita tersembunyi di batu candi — dari kisah hidup Buddha sampai potret kehidupan sehari-hari seribu tahun lalu.',
                    ],
                ],
            ],
            [
                'name' => 'Ijen Crater', 'location' => 'Banyuwangi, East Java, ID',
                'lat' => -8.0581, 'lng' => 114.2422,
                'description_en' => "Ijen is home to one of only two places on Earth where you can see blue fire — a genuinely otherworldly phenomenon caused by burning sulfuric gas. Pair that with a turquoise crater lake (the largest highly acidic lake in the world) and you've got a destination that looks almost too surreal to be real.",
                'description_id' => 'Ijen adalah satu dari cuma dua tempat di dunia yang punya fenomena blue fire — kejadian alam yang beneran kayak bukan dari bumi, disebabkan gas belerang yang terbakar. Digabung sama danau kawah warna toska (danau paling asam terbesar di dunia), jadi destinasi yang keliatannya kayak terlalu surreal buat jadi nyata.',
                'what_to_do_en' => "Set off in the dead of night for a headlamp-lit trek to the crater rim, timed so you catch the blue flames while they're still glowing bright in the dark. Along the way, you'll pass local sulfur miners hauling loads that would make your shoulders ache just looking at them — a humbling reminder of how hard this beauty is earned.",
                'what_to_do_id' => 'Berangkat tengah malam buta buat trekking pakai headlamp menuju bibir kawah, waktunya diatur biar kamu masih sempat lihat blue fire selagi masih nyala terang di kegelapan. Di jalan, kamu bakal papasan sama penambang belerang lokal yang angkut beban berat yang bikin pundak pegel cuma dari lihatnya — pengingat yang bikin merinding soal gimana kerasnya perjuangan di balik keindahan ini.',
                'point_of_interest_en' => 'The electric-blue flames flickering along the crater walls, the strikingly turquoise (and highly acidic) lake below, and the sulfur miners who make this dangerous trek their daily job.',
                'point_of_interest_id' => 'Nyala api biru yang berkelap-kelip di dinding kawah, danau toska yang super asam di bawahnya, dan para penambang belerang yang bikin trekking berbahaya ini jadi kerjaan sehari-hari mereka.',
                'nature' => 5, 'culture' => 3, 'heritage' => 1,
                'cats' => ['Nature', 'Mountain'],
                'photo' => 'https://images.unsplash.com/photo-1578287595011-8c565dfc2ef8?w=1200',
                'cover_photo' => 'destinations/ijen-crater.jpg',
                'activities' => [
                    [
                        'title' => 'Blue Fire Trekking', 'type' => 'adventure',
                        'desc_en' => "A demanding but unforgettable pre-dawn hike, rewarded with a sight so blue and so strange you'll question if it's really fire.",
                        'desc_id' => "Pendakian dini hari yang lumayan berat tapi susah dilupain, dibayar sama pemandangan biru yang aneh banget sampai kamu bakal mikir 'ini beneran api?'.",
                        'photo' => $ijenActivities['blue_fire_photo'],
                    ],
                    ...$ijenActivities['new'],
                ],
            ],
            [
                'name' => 'Tumpak Sewu Waterfall', 'location' => 'Lumajang, East Java, ID',
                'lat' => -8.2091, 'lng' => 112.9250,
                'description_en' => 'Locals call it "Indonesia\'s Niagara," and once you see the tiered curtain of water crashing down in every direction, you\'ll understand why. It\'s not one waterfall — it\'s dozens, all pouring into the same river gorge at once, creating a scene so dramatic it barely looks real in photos.',
                'description_id' => "Warga sekitar nyebut ini 'Niagara-nya Indonesia', dan begitu kamu lihat tirai air bertingkat yang jatuh dari segala arah, kamu bakal ngerti kenapa. Ini bukan satu air terjun — tapi puluhan, semuanya tumpah ke ngarai sungai yang sama secara bersamaan, bikin pemandangan yang dramatis banget sampai kayak gak nyata kalau difoto.",
                'what_to_do_en' => 'Climb down (carefully) through slippery, jungle-lined paths to reach the base of the falls, where you can wade into the pool and feel the spray hit you from every direction. Bring a change of clothes — you will get wet, and you will not regret it.',
                'what_to_do_id' => 'Turun (hati-hati ya) lewat jalan setapak yang licin dan rimbun buat sampai ke dasar air terjun, di mana kamu bisa nyemplung ke kolamnya dan ngerasain percikan air dari segala penjuru. Bawa baju ganti — dijamin basah, tapi gak bakal nyesel.',
                'point_of_interest_en' => 'Panorama Point for the classic wide-angle shot from above, and the raw, up-close chaos of standing at the bottom surrounded by falling water on all sides.',
                'point_of_interest_id' => 'Panorama Point buat foto wide-angle klasik dari atas, dan sensasi mentah berdiri persis di dasarnya dikelilingi air terjun dari segala sisi.',
                'nature' => 5, 'culture' => 1, 'heritage' => 1,
                'cats' => ['Nature'],
                'photo' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=1200',
                'cover_photo' => 'destinations/tumpak-sewu.jpg',
                'activities' => [
                    [
                        'title' => 'River Trekking', 'type' => 'adventure',
                        'desc_en' => 'Follow the river downstream on foot until the roar gets louder and louder, then suddenly — there it is, right in front of you.',
                        'desc_id' => 'Ikutin aliran sungai jalan kaki sampai suara gemuruhnya makin lama makin kenceng, terus tiba-tiba — muncul di depan mata kamu.',
                    ],
                ],
            ],
            [
                'name' => 'Malioboro & Yogyakarta Palace', 'location' => 'Yogyakarta, ID',
                'active' => false,
                'lat' => -7.7930, 'lng' => 110.3654,
                'description_en' => 'If Yogyakarta has a heartbeat, it\'s here. Malioboro Street hums with street vendors, live music, and the smell of grilled satay well into the night, while just a short walk away, the centuries-old Sultan\'s Palace still holds onto royal traditions that outlived colonial rule.',
                'description_id' => 'Kalau Yogyakarta punya detak jantung, ya di sini tempatnya. Jalan Malioboro rame sama pedagang kaki lima, musik jalanan, dan bau sate bakar sampai malam, sementara gak jauh dari situ, Keraton yang udah berumur ratusan tahun masih megang tradisi kerajaan yang bertahan lewat masa penjajahan.',
                'what_to_do_en' => 'Spend the evening browsing batik stalls and haggling for souvenirs along Malioboro, then slow things down with a daytime visit to the Kraton and the water gardens of Taman Sari. When your stomach starts growling, follow the crowds to a lesehan food stall for some of the best street food in Java.',
                'what_to_do_id' => 'Habiskan malam buat lihat-lihat kios batik dan nawar oleh-oleh di Malioboro, terus santai di siang hari dengan kunjungan ke Kraton dan taman air Taman Sari. Kalau perut udah keroncongan, ikutin kerumunan orang ke warung lesehan buat nyicip jajanan kaki lima terenak di Jawa.',
                'point_of_interest_en' => "The Sultan's Palace (Kraton) with its living royal traditions, the atmospheric ruins of Taman Sari's water gardens, and the endless energy of Malioboro Street itself.",
                'point_of_interest_id' => 'Keraton dengan tradisi kerajaan yang masih hidup, reruntuhan bersejarah taman air Taman Sari, dan energi Jalan Malioboro yang gak ada matinya.',
                'nature' => 1, 'culture' => 5, 'heritage' => 4,
                'cats' => ['Culture'],
                'photo' => 'https://images.unsplash.com/photo-1543874768-af0b9c4090d5?w=1200',
                'cover_photo' => 'destinations/malioboro.jpg',
                'activities' => [
                    [
                        'title' => 'Palace Heritage Tour', 'type' => 'tradition',
                        'desc_en' => 'Step inside the walls of a still-functioning royal court and hear stories about the Sultanate that textbooks tend to gloss over.',
                        'desc_id' => 'Masuk ke dalam tembok istana kerajaan yang masih berfungsi dan dengerin cerita-cerita soal Kesultanan yang jarang diceritain lengkap di buku sejarah.',
                    ],
                ],
            ],
            [
                'name' => 'Papuma Beach', 'location' => 'Jember, East Java, ID',
                'active' => false,
                'lat' => -8.4283, 'lng' => 113.5350,
                'description_en' => "Forget the postcard-perfect white sand for a second — Papuma's real showstopper is its coastline of giant, wind-carved rock formations rising straight out of the surf. Add a dramatic southern-sea backdrop and a turtle conservation program working quietly in the background, and you've got a beach with more character than most.",
                'description_id' => 'Lupain dulu sejenak soal pasir putih kayak di kartu pos — daya tarik utama Papuma justru ada di garis pantainya yang dipenuhi batu karang raksasa hasil ukiran angin, berdiri tegak langsung dari ombak. Tambah lagi latar laut selatan yang dramatis dan program konservasi penyu yang jalan diam-diam di baliknya, jadilah pantai yang karakternya beda dari kebanyakan.',
                'what_to_do_en' => "Set up a beach mat and do absolutely nothing for a while, pitch a tent if you're staying overnight, or scramble up the rocky outcrops for a sunset that turns the whole sky orange. If you're around at the right time, ask about the turtle conservation efforts happening nearby.",
                'what_to_do_id' => 'Gelar tikar terus rebahan gak ngapa-ngapain, dirikan tenda kalau mau nginep, atau manjat batu karangnya buat nangkep sunset yang bikin seluruh langit jadi oranye. Kalau lagi pas waktunya, coba tanya soal upaya konservasi penyu yang ada di dekat sana.',
                'point_of_interest_en' => 'The giant rock formations that give the beach its dramatic silhouette, a prime sunset viewing spot, and a low-key turtle conservation area worth a curious visit.',
                'point_of_interest_id' => 'Batu karang raksasa yang bikin siluet pantai ini dramatis, spot terbaik buat nonton sunset, dan area konservasi penyu santai yang worth dikunjungin kalau penasaran.',
                'nature' => 5, 'culture' => 1, 'heritage' => 1,
                'cats' => ['Nature', 'Beach'],
                'photo' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=1200',
                'activities' => [
                    [
                        'title' => 'Sunset at Karang Bopong', 'type' => 'adventure',
                        'desc_en' => 'Scramble up the famous Karang Bopong rock for a front-row seat to one of the best sunsets on the south coast.',
                        'desc_id' => 'Naik ke batu karang Karang Bopong yang terkenal buat dapetin kursi baris depan nonton salah satu sunset terbaik di pantai selatan.',
                    ],
                ],
            ],
            [
                'name' => 'Tanah Lot Temple', 'location' => 'Tabanan, Bali, ID',
                'active' => false,
                'lat' => -8.6212, 'lng' => 115.0868,
                'description_en' => "Tanah Lot is Bali's most photographed temple for good reason — a small Hindu shrine perched dramatically on an offshore rock, surrounded by crashing waves that make it look like it's floating at high tide. Time your visit for sunset and you'll see why it's on every Bali bucket list.",
                'description_id' => 'Tanah Lot jadi candi paling banyak difoto di Bali, dan emang pantas — kuil Hindu kecil yang berdiri dramatis di atas batu karang di tengah laut, dikelilingi ombak yang bikin dia keliatan kayak lagi mengambang pas air pasang. Datang pas sunset dan kamu bakal langsung ngerti kenapa ini masuk bucket list semua orang yang ke Bali.',
                'what_to_do_en' => 'Wander the cliffside path as the tide goes out and the temple rock becomes accessible on foot, browse the little market stalls selling sarongs and snacks, and stick around after dark for a traditional Kecak fire dance performed against the ocean backdrop.',
                'what_to_do_id' => 'Jalan santai di jalur tepi tebing pas air lagi surut dan batu karang candinya bisa didatengin jalan kaki, mampir ke kios-kios kecil yang jual sarung dan cemilan, terus tetep di situ sampai malam buat nonton tari Kecak tradisional dengan latar laut lepas.',
                'point_of_interest_en' => 'The temple rock itself at low tide, that unmistakable sunset silhouette everyone comes for, and the sacred sea-snake cave believed to guard the temple from evil spirits.',
                'point_of_interest_id' => 'Batu karang tempat candi berdiri pas air surut, siluet sunset ikonik yang jadi alasan semua orang dateng ke sini, dan gua ular laut yang dipercaya jaga candi dari roh jahat.',
                'nature' => 3, 'culture' => 5, 'heritage' => 4,
                'cats' => ['Culture', 'Beach'],
                'photo' => 'https://images.unsplash.com/photo-1553902000-e036b7d05af5?w=1200',
                'activities' => [
                    [
                        'title' => 'Sunset Temple Walk', 'type' => 'tradition',
                        'desc_en' => 'A relaxed evening walk along the cliffs with the temple silhouette and the setting sun stealing the whole show.',
                        'desc_id' => 'Jalan santai sore hari di sepanjang tebing dengan siluet candi dan matahari terbenam yang jadi bintang utamanya.',
                    ],
                    [
                        'title' => 'Kecak Fire Dance', 'type' => 'tradition',
                        'desc_en' => 'Settle in for a mesmerizing, fire-lit performance of chanting dancers acting out an ancient Balinese legend.',
                        'desc_id' => 'Duduk santai nikmatin pertunjukan yang bikin merinding — penari berkoor diiringi api, membawakan legenda kuno Bali.',
                    ],
                ],
            ],
        ];

        $destinationTips = require database_path('data/destination_tips.php');
        $destinationGuides = require database_path('data/destination_guides.php');

        $destinations = [];
        foreach ($destinationsData as $d) {
            $destination = Destination::firstOrCreate(
                ['slug' => Str::slug($d['name'])],
                [
                    'name' => $d['name'],
                    'location' => $d['location'],
                    'latitude' => $d['lat'],
                    'longitude' => $d['lng'],
                    'description_en' => $d['description_en'],
                    'description_id' => $d['description_id'],
                    'what_to_do_en' => $d['what_to_do_en'],
                    'what_to_do_id' => $d['what_to_do_id'],
                    'point_of_interest_en' => $d['point_of_interest_en'],
                    'point_of_interest_id' => $d['point_of_interest_id'],
                    'nature_level' => $d['nature'],
                    'culture_level' => $d['culture'],
                    'heritage_level' => $d['heritage'],
                    'tips_en' => $destinationTips[Str::slug($d['name'])]['en'] ?? null,
                    'tips_id' => $destinationTips[Str::slug($d['name'])]['id'] ?? null,
                    'best_months' => isset($destinationGuides[Str::slug($d['name'])]) ? implode(',', $destinationGuides[Str::slug($d['name'])]['months']) : null,
                    'best_time_en' => $destinationGuides[Str::slug($d['name'])]['best_time']['en'] ?? null,
                    'best_time_id' => $destinationGuides[Str::slug($d['name'])]['best_time']['id'] ?? null,
                    'packing_en' => $destinationGuides[Str::slug($d['name'])]['packing']['en'] ?? null,
                    'packing_id' => $destinationGuides[Str::slug($d['name'])]['packing']['id'] ?? null,
                    'provided_en' => $destinationGuides[Str::slug($d['name'])]['provided']['en'] ?? null,
                    'provided_id' => $destinationGuides[Str::slug($d['name'])]['provided']['id'] ?? null,
                    'safety_en' => $destinationGuides[Str::slug($d['name'])]['safety']['en'] ?? null,
                    'safety_id' => $destinationGuides[Str::slug($d['name'])]['safety']['id'] ?? null,
                    'cover_photo' => $d['cover_photo'] ?? $d['photo'],
                    'is_active' => $d['active'] ?? true,
                    'created_by' => $admin->id,
                ]
            );

            $destination->categories()->sync(collect($d['cats'])->map(fn ($c) => $catByName($c)?->id)->filter());

            foreach ($d['activities'] as $a) {
                Activity::firstOrCreate(
                    ['destination_id' => $destination->id, 'title' => $a['title']],
                    ['title_id' => $activityTitlesId[$a['title']] ?? null, 'description_en' => $a['desc_en'], 'description_id' => $a['desc_id'], 'type' => $a['type'], 'photo' => $a['photo'] ?? $d['photo']]
                );
            }

            $destinations[$d['name']] = $destination;
        }

        // ---------- Packages ----------
        $bromo = Package::firstOrCreate(
            ['slug' => 'bromo-sunrise-trip'],
            [
                'category_id' => $catByName('One Day Tour')?->id,
                'name' => 'Bromo Sunrise Trip',
                'description_en' => 'One sunrise, zero regrets. Wake up ridiculously early, catch Bromo\'s legendary sunrise from Penanjakan, then spend the rest of the morning tearing across the sand sea and peering into the smoking crater before heading home — all in a single, jam-packed day.',
                'description_id' => 'Satu sunrise, nol penyesalan. Bangun pagi buta, nonton sunrise legendaris Bromo dari Penanjakan, terus habiskan sisa paginya ngebut nyebrangin lautan pasir dan ngintip kawah yang masih ngebul sebelum pulang — semuanya dalam satu hari yang padat.',
                'cover_photo' => $destinations['Mount Bromo']->cover_photo,
                'is_featured' => true,
                'duration_days' => 1,
                'start_city' => 'Malang',
                'end_city' => 'Malang',
                'created_by' => $admin->id,
            ]
        );
        $bromo->destinations()->sync([$destinations['Mount Bromo']->id => ['order' => 0]]);
        PackagePlan::firstOrCreate(['package_id' => $bromo->id, 'name' => 'Transportation Only'], ['price' => 60, 'features' => ['Transportation', 'Flexible Meeting Point'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $bromo->id, 'name' => 'All In Package'], ['price' => 80, 'features' => ['Transportation', 'Flexible Meeting Point', 'Snack', 'Entrance Ticket'], 'is_recommended' => true]);

        $sunrise = Package::firstOrCreate(
            ['slug' => 'bromo-sunrise-only'],
            [
                'category_id' => $catByName('Sunrise')?->id,
                'name' => 'Bromo Sunrise Only',
                'description_en' => "For when you just want the money shot without the extra hours. Straight to Penanjakan, straight to the best sunrise view in East Java, no detours.",
                'description_id' => 'Buat kamu yang cuma mau moment terbaiknya aja tanpa buang-buang waktu. Langsung ke Penanjakan, langsung dapet pemandangan sunrise terbaik di Jawa Timur, tanpa muter-muter.',
                'cover_photo' => 'packages/bromo-sunrise-only.jpg',
                'duration_days' => 1,
                'start_city' => 'Malang',
                'end_city' => 'Malang',
                'created_by' => $admin->id,
            ]
        );
        $sunrise->destinations()->sync([$destinations['Mount Bromo']->id => ['order' => 0]]);
        PackagePlan::firstOrCreate(['package_id' => $sunrise->id, 'name' => 'Standard'], ['price' => 45, 'features' => ['Transportation', 'Meeting Point'], 'is_recommended' => true]);

        $caveBeach = Package::firstOrCreate(
            ['slug' => 'tumpak-sewu-kawah-ijen'],
            [
                'category_id' => $catByName('Cave & Beach')?->id,
                'name' => 'Tumpak Sewu & Ijen Crater',
                'description_en' => "Two of East Java's most jaw-dropping natural wonders, back to back: get soaked at the thundering tiers of Tumpak Sewu, then trade daylight for darkness as you chase Ijen's otherworldly blue fire.",
                'description_id' => 'Dua keajaiban alam paling bikin melongo di Jawa Timur, digabung jadi satu: basah-basahan di air terjun bertingkat Tumpak Sewu yang menggelegar, terus tukar siang jadi malam buat ngejar blue fire Ijen yang kayak bukan dari bumi.',
                'cover_photo' => 'packages/tumpak-sewu-kawah-ijen.jpg',
                'is_featured' => true,
                'duration_days' => 2,
                'start_city' => 'Malang',
                'end_city' => 'Banyuwangi',
                'created_by' => $admin->id,
            ]
        );
        $caveBeach->destinations()->sync([
            $destinations['Tumpak Sewu Waterfall']->id => ['order' => 0],
            $destinations['Ijen Crater']->id => ['order' => 1],
        ]);
        PackagePlan::firstOrCreate(['package_id' => $caveBeach->id, 'name' => 'Transportation Only'], ['price' => 70, 'features' => ['Transportation'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $caveBeach->id, 'name' => 'All In Package'], ['price' => 95, 'features' => ['Transportation', 'Snack', 'Entrance Ticket', 'Local Guide'], 'is_recommended' => true]);

        $overland = Package::firstOrCreate(
            ['slug' => 'jogja-borobudur-bromo-tumpak-sewu-kawah-ijen'],
            [
                'category_id' => $catByName('Overland')?->id,
                'name' => 'Jogja/Borobudur > Bromo > Tumpak Sewu > Ijen Crater',
                'description_en' => 'The ultimate East Java overland run. Seven days, four unforgettable stops: sunrise over a thousand-year-old temple, a volcanic sand sea straight out of a sci-fi movie, a waterfall that dwarfs Niagara, and a fire that burns blue. Bring good shoes and an empty memory card.',
                'description_id' => 'Rute overland Jawa Timur paling lengkap. Tujuh hari, empat destinasi yang gak bakal kelupa: sunrise di candi berumur seribu tahun, lautan pasir vulkanik yang kayak dari film sci-fi, air terjun yang bikin Niagara kalah, dan api yang nyalanya biru. Siapin sepatu yang nyaman dan memori kamera yang kosong.',
                'cover_photo' => $destinations['Ijen Crater']->cover_photo,
                'is_featured' => true,
                'duration_days' => 7,
                'start_city' => 'Yogyakarta',
                'end_city' => 'Banyuwangi',
                'created_by' => $admin->id,
            ]
        );
        $overland->destinations()->sync([
            $destinations['Malioboro & Yogyakarta Palace']->id => ['order' => 0],
            $destinations['Borobudur Temple']->id => ['order' => 1],
            $destinations['Mount Bromo']->id => ['order' => 2],
            $destinations['Tumpak Sewu Waterfall']->id => ['order' => 3],
            $destinations['Ijen Crater']->id => ['order' => 4],
        ]);
        PackagePlan::firstOrCreate(['package_id' => $overland->id, 'name' => 'Transportation Only'], ['price' => 350, 'features' => ['Transportation', 'Driver'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $overland->id, 'name' => 'All In Package'], ['price' => 520, 'features' => ['Transportation', 'Driver', 'Accommodation', 'Meals', 'Entrance Tickets'], 'is_recommended' => true]);

        $itineraries = [
            ['Day 1-2', 'Hari 1-2', 'Arrive in Jogja, city tour of Malioboro & the Palace, sunrise tour at Borobudur Temple.', 'Tiba di Jogja, city tour ke Malioboro & Keraton, sunrise tour di Candi Borobudur.'],
            ['Day 3-4', 'Hari 3-4', 'Travel to Bromo, jeep sunrise tour at Penanjakan, explore the sand sea.', 'Perjalanan menuju Bromo, jeep sunrise tour di Penanjakan, jelajahi lautan pasir.'],
            ['Day 5-6', 'Hari 5-6', 'Trek Tumpak Sewu Waterfall, continue to the Ijen Crater base camp.', 'Trekking ke Air Terjun Tumpak Sewu, lanjut menuju base camp Kawah Ijen.'],
            ['Day 7', 'Hari 7', 'Early-morning blue fire trek at Ijen Crater, return trip / drop-off in Banyuwangi.', 'Trekking blue fire dini hari di Kawah Ijen, perjalanan pulang / drop-off di Banyuwangi.'],
        ];
        foreach ($itineraries as $i => [$labelEn, $labelId, $descEn, $descId]) {
            PackageItinerary::firstOrCreate(
                ['package_id' => $overland->id, 'day_label_en' => $labelEn],
                ['day_label_id' => $labelId, 'description_en' => $descEn, 'description_id' => $descId, 'order' => $i]
            );
        }

        // Flagship long route extended to Bali. Prices below are demo placeholders reasoned
        // proportionally from the 7-day Overland package ($350/$520) for ~3 extra days — adjust to real costs.
        $overlandBali = Package::firstOrCreate(
            ['slug' => 'jogja-borobudur-bromo-tumpak-sewu-kawah-ijen-bali'],
            [
                'category_id' => $catByName('Overland')?->id,
                'name' => 'Jogja/Borobudur > Bromo > Tumpak Sewu > Ijen Crater > Bali',
                'description_en' => 'The full Java-to-Bali overland experience. Everything from the classic 7-day route, plus a ferry crossing over the Bali Strait and a few extra days to unwind in Bali, capped off with a golden sunset at Tanah Lot. Ten days, two islands, memories for a lifetime.',
                'description_id' => 'Pengalaman overland lengkap dari Jawa sampai Bali. Semua yang ada di rute klasik 7 hari, ditambah nyebrang Selat Bali naik kapal feri dan beberapa hari ekstra buat santai-santai di Bali, ditutup sama sunset keemasan di Tanah Lot. Sepuluh hari, dua pulau, kenangan seumur hidup.',
                'cover_photo' => $destinations['Tanah Lot Temple']->cover_photo,
                'is_featured' => true,
                'capacity' => 8,
                'duration_days' => 10,
                'start_city' => 'Yogyakarta',
                'end_city' => 'Denpasar',
                'created_by' => $admin->id,
            ]
        );
        $overlandBali->destinations()->sync([
            $destinations['Malioboro & Yogyakarta Palace']->id => ['order' => 0],
            $destinations['Borobudur Temple']->id => ['order' => 1],
            $destinations['Mount Bromo']->id => ['order' => 2],
            $destinations['Tumpak Sewu Waterfall']->id => ['order' => 3],
            $destinations['Ijen Crater']->id => ['order' => 4],
            $destinations['Tanah Lot Temple']->id => ['order' => 5],
        ]);
        PackagePlan::firstOrCreate(['package_id' => $overlandBali->id, 'name' => 'Transportation Only'], ['price' => 460, 'features' => ['Transportation', 'Driver'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $overlandBali->id, 'name' => 'All In Package'], ['price' => 680, 'features' => ['Transportation', 'Driver', 'Accommodation', 'Meals', 'Entrance Tickets'], 'is_recommended' => true]);

        $overlandBaliItineraries = [
            ['Day 1-2', 'Hari 1-2', 'Arrive in Jogja, city tour of Malioboro & the Palace, sunrise tour at Borobudur Temple.', 'Tiba di Jogja, city tour ke Malioboro & Keraton, sunrise tour di Candi Borobudur.'],
            ['Day 3-4', 'Hari 3-4', 'Travel to Bromo, jeep sunrise tour at Penanjakan, explore the sand sea.', 'Perjalanan menuju Bromo, jeep sunrise tour di Penanjakan, jelajahi lautan pasir.'],
            ['Day 5-6', 'Hari 5-6', 'Trek Tumpak Sewu Waterfall, continue to the Ijen Crater base camp.', 'Trekking ke Air Terjun Tumpak Sewu, lanjut menuju base camp Kawah Ijen.'],
            ['Day 7', 'Hari 7', 'Early-morning blue fire trek at Ijen Crater, then cross the Bali Strait by ferry from Ketapang to Gilimanuk.', 'Trekking blue fire dini hari di Kawah Ijen, lanjut nyebrang Selat Bali naik feri dari Ketapang ke Gilimanuk.'],
            ['Day 8-9', 'Hari 8-9', 'Explore Bali: sunset at Tanah Lot Temple, relax and enjoy local culture.', 'Jelajahi Bali: sunset di Tanah Lot, santai dan nikmatin budaya lokal.'],
            ['Day 10', 'Hari 10', 'Drop-off in Denpasar/Kuta.', 'Drop-off di Denpasar/Kuta.'],
        ];
        foreach ($overlandBaliItineraries as $i => [$labelEn, $labelId, $descEn, $descId]) {
            PackageItinerary::firstOrCreate(
                ['package_id' => $overlandBali->id, 'day_label_en' => $labelEn],
                ['day_label_id' => $labelId, 'description_en' => $descEn, 'description_id' => $descId, 'order' => $i]
            );
        }

        // Shorter variant that drops travelers at Malang/Surabaya instead of continuing past Bromo.
        // Prices are demo placeholders reasoned between the 1-day Bromo trip and the 7-day Overland — adjust to real costs.
        $malangSurabaya = Package::firstOrCreate(
            ['slug' => 'jogja-borobudur-bromo-drop-malang-surabaya'],
            [
                'category_id' => $catByName('Overland')?->id,
                'name' => 'Jogja/Borobudur > Bromo (Drop Malang/Surabaya)',
                'description_en' => 'Short on time but not on adventure. Four days from Jogja to a Bromo sunrise you\'ll never forget, with a convenient drop-off in Malang or Surabaya so you can carry on your journey (or fly home) without doubling back.',
                'description_id' => 'Waktu terbatas, petualangan tetap maksimal. Empat hari dari Jogja sampai sunrise Bromo yang gak bakal kelupa, dengan drop-off praktis di Malang atau Surabaya biar kamu bisa lanjut perjalanan (atau langsung pulang) tanpa harus balik arah.',
                'cover_photo' => $destinations['Malioboro & Yogyakarta Palace']->cover_photo,
                'duration_days' => 4,
                'start_city' => 'Yogyakarta',
                'end_city' => 'Malang/Surabaya',
                'created_by' => $admin->id,
            ]
        );
        $malangSurabaya->destinations()->sync([
            $destinations['Malioboro & Yogyakarta Palace']->id => ['order' => 0],
            $destinations['Borobudur Temple']->id => ['order' => 1],
            $destinations['Mount Bromo']->id => ['order' => 2],
        ]);
        PackagePlan::firstOrCreate(['package_id' => $malangSurabaya->id, 'name' => 'Transportation Only'], ['price' => 180, 'features' => ['Transportation', 'Driver'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $malangSurabaya->id, 'name' => 'All In Package'], ['price' => 260, 'features' => ['Transportation', 'Driver', 'Accommodation', 'Meals'], 'is_recommended' => true]);

        $malangSurabayaItineraries = [
            ['Day 1-2', 'Hari 1-2', 'Arrive in Jogja, city tour of Malioboro & the Palace, sunrise tour at Borobudur Temple.', 'Tiba di Jogja, city tour ke Malioboro & Keraton, sunrise tour di Candi Borobudur.'],
            ['Day 3', 'Hari 3', 'Travel to Bromo, jeep sunrise tour at Penanjakan, explore the sand sea.', 'Perjalanan menuju Bromo, jeep sunrise tour di Penanjakan, jelajahi lautan pasir.'],
            ['Day 4', 'Hari 4', "Drop-off at Malang or Surabaya (traveler's choice).", 'Drop-off di Malang atau Surabaya (sesuai pilihan traveler).'],
        ];
        foreach ($malangSurabayaItineraries as $i => [$labelEn, $labelId, $descEn, $descId]) {
            PackageItinerary::firstOrCreate(
                ['package_id' => $malangSurabaya->id, 'day_label_en' => $labelEn],
                ['day_label_id' => $labelId, 'description_en' => $descEn, 'description_id' => $descId, 'order' => $i]
            );
        }

        // Shortest variant: ends right after Bromo, no further stops, flexible self-arranged onward travel.
        // Prices are demo placeholders — adjust to real costs.
        $bromoDrop = Package::firstOrCreate(
            ['slug' => 'jogja-borobudur-bromo-drop'],
            [
                'category_id' => $catByName('Overland')?->id,
                'name' => 'Jogja/Borobudur > Bromo (Drop)',
                'description_en' => "The essentials, nothing more. Three days covering Jogja's icons and a Bromo sunrise, then you're free to carry on your own way — no fixed onward drop-off, just the highlights done right.",
                'description_id' => 'Yang penting-penting aja, gak pakai lama. Tiga hari nyakup ikon-ikon Jogja dan sunrise Bromo, abis itu kamu bebas lanjut sendiri — gak ada drop-off tetap, cuma highlight-nya aja yang digarap maksimal.',
                'cover_photo' => 'packages/jogja-borobudur-bromo-drop.jpg',
                'duration_days' => 3,
                'start_city' => 'Yogyakarta',
                'end_city' => 'Bromo',
                'created_by' => $admin->id,
            ]
        );
        $bromoDrop->destinations()->sync([
            $destinations['Malioboro & Yogyakarta Palace']->id => ['order' => 0],
            $destinations['Borobudur Temple']->id => ['order' => 1],
            $destinations['Mount Bromo']->id => ['order' => 2],
        ]);
        PackagePlan::firstOrCreate(['package_id' => $bromoDrop->id, 'name' => 'Transportation Only'], ['price' => 150, 'features' => ['Transportation', 'Driver'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $bromoDrop->id, 'name' => 'All In Package'], ['price' => 220, 'features' => ['Transportation', 'Driver', 'Accommodation', 'Meals'], 'is_recommended' => true]);

        $bromoDropItineraries = [
            ['Day 1-2', 'Hari 1-2', 'Arrive in Jogja, city tour of Malioboro & the Palace, sunrise tour at Borobudur Temple.', 'Tiba di Jogja, city tour ke Malioboro & Keraton, sunrise tour di Candi Borobudur.'],
            ['Day 3', 'Hari 3', 'Travel to Bromo, jeep sunrise tour at Penanjakan, explore the sand sea. Trip ends here, onward travel is on your own.', 'Perjalanan menuju Bromo, jeep sunrise tour di Penanjakan, jelajahi lautan pasir. Trip selesai di sini, lanjut perjalanan sendiri.'],
        ];
        foreach ($bromoDropItineraries as $i => [$labelEn, $labelId, $descEn, $descId]) {
            PackageItinerary::firstOrCreate(
                ['package_id' => $bromoDrop->id, 'day_label_en' => $labelEn],
                ['day_label_id' => $labelId, 'description_en' => $descEn, 'description_id' => $descId, 'order' => $i]
            );
        }

        // Mid-length variant: continues past Bromo to Tumpak Sewu, stops there (no Ijen, no Bali).
        // Prices are demo placeholders — adjust to real costs.
        $tumpakSewuDrop = Package::firstOrCreate(
            ['slug' => 'jogja-borobudur-bromo-tumpak-sewu'],
            [
                'category_id' => $catByName('Overland')?->id,
                'name' => 'Jogja/Borobudur > Bromo > Tumpak Sewu',
                'description_en' => "Jogja's temples, Bromo's sunrise, and the thundering tiers of Tumpak Sewu — five days that hit three of Java's biggest highlights without stretching all the way to Ijen.",
                'description_id' => 'Candi-candi Jogja, sunrise Bromo, dan gemuruh air terjun Tumpak Sewu — lima hari yang nyakup tiga highlight terbesar Jawa tanpa harus lanjut sampai Ijen.',
                'cover_photo' => $destinations['Tumpak Sewu Waterfall']->cover_photo,
                'duration_days' => 5,
                'start_city' => 'Yogyakarta',
                'end_city' => 'Tumpak Sewu',
                'created_by' => $admin->id,
            ]
        );
        $tumpakSewuDrop->destinations()->sync([
            $destinations['Malioboro & Yogyakarta Palace']->id => ['order' => 0],
            $destinations['Borobudur Temple']->id => ['order' => 1],
            $destinations['Mount Bromo']->id => ['order' => 2],
            $destinations['Tumpak Sewu Waterfall']->id => ['order' => 3],
        ]);
        PackagePlan::firstOrCreate(['package_id' => $tumpakSewuDrop->id, 'name' => 'Transportation Only'], ['price' => 280, 'features' => ['Transportation', 'Driver'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $tumpakSewuDrop->id, 'name' => 'All In Package'], ['price' => 400, 'features' => ['Transportation', 'Driver', 'Accommodation', 'Meals', 'Entrance Tickets'], 'is_recommended' => true]);

        $tumpakSewuDropItineraries = [
            ['Day 1-2', 'Hari 1-2', 'Arrive in Jogja, city tour of Malioboro & the Palace, sunrise tour at Borobudur Temple.', 'Tiba di Jogja, city tour ke Malioboro & Keraton, sunrise tour di Candi Borobudur.'],
            ['Day 3-4', 'Hari 3-4', 'Travel to Bromo, jeep sunrise tour at Penanjakan, explore the sand sea.', 'Perjalanan menuju Bromo, jeep sunrise tour di Penanjakan, jelajahi lautan pasir.'],
            ['Day 5', 'Hari 5', 'Trek Tumpak Sewu Waterfall. Trip ends here, onward travel is on your own.', 'Trekking ke Air Terjun Tumpak Sewu. Trip selesai di sini, lanjut perjalanan sendiri.'],
        ];
        foreach ($tumpakSewuDropItineraries as $i => [$labelEn, $labelId, $descEn, $descId]) {
            PackageItinerary::firstOrCreate(
                ['package_id' => $tumpakSewuDrop->id, 'day_label_en' => $labelEn],
                ['day_label_id' => $labelId, 'description_en' => $descEn, 'description_id' => $descId, 'order' => $i]
            );
        }

        // Same stops as the Tumpak Sewu variant above, but with an explicit drop-off day at Malang/Surabaya.
        // Prices are demo placeholders — adjust to real costs.
        $tumpakSewuMalangSurabaya = Package::firstOrCreate(
            ['slug' => 'jogja-borobudur-bromo-tumpak-sewu-drop-malang-surabaya'],
            [
                'category_id' => $catByName('Overland')?->id,
                'name' => 'Jogja/Borobudur > Bromo > Tumpak Sewu (Drop Malang/Surabaya)',
                'description_en' => 'All the highlights of the Tumpak Sewu route — Jogja, Bromo, and the waterfall itself — wrapped up with a convenient drop-off in Malang or Surabaya so your next flight or train is one less thing to plan around.',
                'description_id' => 'Semua highlight rute Tumpak Sewu — Jogja, Bromo, dan air terjunnya sendiri — ditutup dengan drop-off praktis di Malang atau Surabaya biar penerbangan atau kereta selanjutnya gak perlu dipusingin lagi.',
                'cover_photo' => 'packages/jogja-borobudur-bromo-tumpak-sewu-drop-malang-surabaya.jpg',
                'duration_days' => 6,
                'start_city' => 'Yogyakarta',
                'end_city' => 'Malang/Surabaya',
                'created_by' => $admin->id,
            ]
        );
        $tumpakSewuMalangSurabaya->destinations()->sync([
            $destinations['Malioboro & Yogyakarta Palace']->id => ['order' => 0],
            $destinations['Borobudur Temple']->id => ['order' => 1],
            $destinations['Mount Bromo']->id => ['order' => 2],
            $destinations['Tumpak Sewu Waterfall']->id => ['order' => 3],
        ]);
        PackagePlan::firstOrCreate(['package_id' => $tumpakSewuMalangSurabaya->id, 'name' => 'Transportation Only'], ['price' => 310, 'features' => ['Transportation', 'Driver'], 'is_recommended' => false]);
        PackagePlan::firstOrCreate(['package_id' => $tumpakSewuMalangSurabaya->id, 'name' => 'All In Package'], ['price' => 440, 'features' => ['Transportation', 'Driver', 'Accommodation', 'Meals', 'Entrance Tickets'], 'is_recommended' => true]);

        $tumpakSewuMalangSurabayaItineraries = [
            ['Day 1-2', 'Hari 1-2', 'Arrive in Jogja, city tour of Malioboro & the Palace, sunrise tour at Borobudur Temple.', 'Tiba di Jogja, city tour ke Malioboro & Keraton, sunrise tour di Candi Borobudur.'],
            ['Day 3-4', 'Hari 3-4', 'Travel to Bromo, jeep sunrise tour at Penanjakan, explore the sand sea.', 'Perjalanan menuju Bromo, jeep sunrise tour di Penanjakan, jelajahi lautan pasir.'],
            ['Day 5', 'Hari 5', 'Trek Tumpak Sewu Waterfall.', 'Trekking ke Air Terjun Tumpak Sewu.'],
            ['Day 6', 'Hari 6', "Drop-off at Malang or Surabaya (traveler's choice).", 'Drop-off di Malang atau Surabaya (sesuai pilihan traveler).'],
        ];
        foreach ($tumpakSewuMalangSurabayaItineraries as $i => [$labelEn, $labelId, $descEn, $descId]) {
            PackageItinerary::firstOrCreate(
                ['package_id' => $tumpakSewuMalangSurabaya->id, 'day_label_en' => $labelEn],
                ['day_label_id' => $labelId, 'description_en' => $descEn, 'description_id' => $descId, 'order' => $i]
            );
        }

        // ---------- Articles ----------
        $articles = [
            [
                'title_en' => "5 Things to Pack Before You Chase the Bromo Sunrise",
                'title_id' => 'Barang Wajib Bawa Sebelum Ngejar Sunrise Bromo',
                'excerpt_en' => "Freezing pre-dawn air, ash-covered trails, and a view that makes it all worth it — here's exactly what to bring so nothing catches you off guard.",
                'excerpt_id' => 'Udara dini hari yang bikin menggigil, jalur berdebu vulkanik, dan pemandangan yang bikin semuanya worth it — ini yang harus kamu siapin biar gak kaget di lapangan.',
                'content_en' => "Let's be honest: nobody warns you enough about how cold Bromo gets before sunrise. Temperatures can drop close to freezing at Penanjakan, so a thin jacket just won't cut it — bring something thick, plus a scarf and gloves if you have them.\n\nNext, timing is everything. Jeeps start queuing for the best spots at Penanjakan well before the sky even starts to lighten, so if you want an unobstructed view (and not someone's phone in your photo), get there early.\n\nOnce the sun's up and you're crossing the sand sea, the volcanic dust kicks up fast — a simple mask or bandana over your nose and mouth makes a huge difference. Sturdy shoes matter too; the terrain is uneven and can get slippery near the crater rim.\n\nLastly, charge everything the night before. Between the cold draining your battery and the sheer number of photos you'll want to take, a power bank is basically mandatory. Come prepared, and Bromo will reward you with one of the best sunrises of your life.",
                'content_id' => "Jujur aja, jarang ada yang bener-bener ngingetin seberapa dingin Bromo sebelum sunrise. Suhu di Penanjakan bisa mendekati titik beku, jadi jaket tipis aja gak bakal cukup — bawa yang tebal, plus syal dan sarung tangan kalau punya.\n\nSelanjutnya, timing itu segalanya. Jip-jip udah mulai antre di spot terbaik Penanjakan jauh sebelum langit mulai terang, jadi kalau mau pemandangan yang gak keganggu (dan gak ada hp orang lain nongol di foto kamu), datang lebih pagi.\n\nBegitu matahari udah naik dan kamu mulai nyebrangin lautan pasir, debu vulkaniknya cepet banget beterbangan — masker simpel atau bandana yang nutupin hidung dan mulut bakal ngebantu banget. Sepatu yang kokoh juga penting; medannya gak rata dan bisa licin deket bibir kawah.\n\nTerakhir, cas semua alat elektronik malam sebelumnya. Antara baterai yang cepet abis karena dingin dan banyaknya foto yang bakal kamu ambil, power bank itu wajib hukumnya. Siap-siap dengan baik, dan Bromo bakal ngasih kamu salah satu sunrise terbaik seumur hidup.",
                'cat' => 'Travel Tips',
            ],
            [
                'title_en' => "The Stories Hidden in Borobudur's 2,600 Relief Panels",
                'title_id' => 'Cerita Tersembunyi di 2.600 Relief Candi Borobudur',
                'excerpt_en' => "Every wall at Borobudur is basically a stone comic book — here's how to actually read it.",
                'excerpt_id' => 'Setiap dinding di Borobudur itu kayak komik batu — ini cara biar kamu beneran bisa "baca" ceritanya.',
                'content_en' => "Most visitors walk past Borobudur's reliefs without realizing they're looking at one of the largest visual narratives ever carved in stone. Spread across more than 2,600 panels, the reliefs tell everything from the life of the Buddha to scenes of everyday life in ancient Java — farmers, merchants, musicians, even ships sailing to distant lands.\n\nThere's a right way to experience it, too: walking clockwise around each level, a practice called pradakshina. Pilgrims have done this for over a thousand years, and it's still considered the respectful way to explore the temple today. As you climb higher, the reliefs shift from worldly desires to spiritual enlightenment, mirroring the journey the temple was designed to represent.\n\nBring a guide if you can — a lot of the smaller details (like the ship carving thought to depict ancient trade routes to Madagascar) are easy to miss if you don't know where to look. But even without one, just slowing down and actually looking at the carvings turns a quick photo stop into something a lot more memorable.",
                'content_id' => "Kebanyakan pengunjung jalan lewatin relief Borobudur tanpa sadar kalau mereka lagi liat salah satu narasi visual terbesar yang pernah diukir di batu. Tersebar di lebih dari 2.600 panel, relief ini nyeritain segalanya, dari kehidupan Buddha sampai potret keseharian masyarakat Jawa kuno — petani, pedagang, musisi, bahkan kapal yang berlayar ke negeri jauh.\n\nAda cara yang 'benar' buat menikmatinya juga: jalan searah jarum jam di tiap tingkat, tradisi yang disebut pradaksina. Para peziarah udah ngelakuin ini lebih dari seribu tahun, dan sampai sekarang masih dianggap cara yang sopan buat menjelajahi candi. Makin naik ke atas, reliefnya bergeser dari hasrat duniawi ke pencerahan spiritual, ngegambarin perjalanan yang emang dirancang buat direpresentasikan candi ini.\n\nKalau bisa, ajak pemandu — banyak detail kecil (kayak ukiran kapal yang dipercaya nggambarin jalur dagang kuno ke Madagaskar) gampang kelewat kalau kamu gak tau harus liat ke mana. Tapi walau tanpa pemandu, cuma dengan santai dan bener-bener merhatiin ukirannya aja udah bisa ngubah sekadar foto-foto jadi pengalaman yang jauh lebih berkesan.",
                'cat' => 'Culture Story',
            ],
            [
                'title_en' => "Blue Fire at Ijen: Why It's Worth the 1AM Wake-Up Call",
                'title_id' => 'Blue Fire Ijen: Alasan Kenapa Worth Banget Bangun Jam 1 Pagi',
                'excerpt_en' => "There are only two places on the planet where you can see this. One of them is a 2-3 hour hike in the pitch dark. Here's why people still do it.",
                'excerpt_id' => 'Cuma ada dua tempat di bumi yang punya fenomena ini. Salah satunya butuh trekking 2-3 jam dalam gelap gulita. Ini alasan kenapa orang tetep pada mau.',
                'content_en' => "It sounds made up until you see it with your own eyes: flames that burn electric blue, flickering along the cracks of an active volcanic crater. Ijen is one of only two places in the world where this happens, caused by sulfuric gas igniting the moment it hits oxygen at extremely high temperatures.\n\nThe catch is that it's only visible clearly in complete darkness, which means most trekkers start hiking around 1AM to reach the crater rim before the flames fade with the coming daylight. It's cold, it's steep in places, and your legs will feel it the next day — but the reward is a sight almost nobody gets to see back home.\n\nAlong the trail, you'll likely pass local sulfur miners making the same climb, except they're doing it to carry loads of solid sulfur down the mountain by hand, often twice a day. It's hard not to feel a mix of awe and respect watching them work in a place tourists visit purely for the view. If you only do one blue fire trek in your life, let it be this one.",
                'content_id' => "Kedengarannya kayak dibuat-buat sampai kamu lihat sendiri: api yang nyalanya biru terang, berkelap-kelip di celah kawah gunung berapi aktif. Ijen adalah satu dari cuma dua tempat di dunia yang punya fenomena ini, disebabkan gas belerang yang langsung terbakar begitu kena oksigen di suhu yang super tinggi.\n\nMasalahnya, ini cuma keliatan jelas dalam kegelapan total, jadi kebanyakan pendaki mulai trekking sekitar jam 1 pagi biar sampai bibir kawah sebelum apinya memudar kena cahaya matahari. Dingin, medannya curam di beberapa titik, dan kaki kamu bakal berasa banget besoknya — tapi hasilnya adalah pemandangan yang hampir gak ada orang lain di kampung halaman kamu yang pernah lihat.\n\nDi sepanjang jalur, kamu bakal papasan sama penambang belerang lokal yang naik gunung yang sama, bedanya mereka ngelakuin itu buat angkut bongkahan belerang padat turun gunung pakai tangan, seringnya dua kali sehari. Susah buat gak ngerasa campuran kagum dan hormat lihat mereka kerja di tempat yang wisatawan datengin cuma buat liat pemandangan doang. Kalau kamu cuma mau ngelakuin satu trekking blue fire seumur hidup, biarin ini yang jadi pilihannya.",
                'cat' => 'Travel Tips',
            ],
        ];
        foreach ($articles as $a) {
            Article::firstOrCreate(
                ['slug' => Str::slug($a['title_en'])],
                [
                    'category_id' => $catByName($a['cat'])?->id,
                    'author_id' => $admin->id,
                    'title_en' => $a['title_en'],
                    'title_id' => $a['title_id'],
                    'excerpt_en' => $a['excerpt_en'],
                    'excerpt_id' => $a['excerpt_id'],
                    'content_en' => $a['content_en'],
                    'content_id' => $a['content_id'],
                    'cover_photo' => $destinations['Mount Bromo']->cover_photo,
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }

        // ---------- Events (shown in homepage hero carousel) ----------
        $events = [
            [
                'title_en' => 'Bromo Yadnya Kasada Festival',
                'title_id' => 'Festival Yadnya Kasada Bromo',
                'description_en' => "Once a year, the Tenggerese people climb to the rim of Bromo's crater at dawn to make offerings to the mountain — a centuries-old ceremony you can witness up close. We're running a special small-group trip timed around the festival dates, book early as spots fill fast.",
                'description_id' => 'Setahun sekali, masyarakat Tengger naik ke bibir kawah Bromo saat subuh buat ngasih sesajen ke gunung — upacara berusia ratusan tahun yang bisa kamu saksikan langsung dari dekat. Kami buka trip grup kecil khusus pas tanggal festivalnya, buruan booking sebelum slotnya penuh.',
                'cover_photo' => $destinations['Mount Bromo']->cover_photo,
                'event_date' => '2027-06-15',
            ],
            [
                'title_en' => 'Ijen Blue Fire Photography Trip',
                'title_id' => 'Trip Foto Blue Fire Ijen',
                'description_en' => "A special departure for photographers: extra time at the crater rim before sunrise, a slower pace, and a guide who knows exactly where the blue flames burn brightest. Bring a tripod.",
                'description_id' => 'Keberangkatan khusus buat fotografer: waktu ekstra di bibir kawah sebelum subuh, ritme yang lebih santai, dan pemandu yang hafal betul titik blue fire paling terang. Jangan lupa bawa tripod.',
                'cover_photo' => 'events/ijen-blue-fire-photography-trip.jpg',
                'event_date' => '2026-11-20',
            ],
        ];
        foreach ($events as $e) {
            Event::firstOrCreate(
                ['title_en' => $e['title_en']],
                [
                    'title_id' => $e['title_id'],
                    'description_en' => $e['description_en'],
                    'description_id' => $e['description_id'],
                    'cover_photo' => $e['cover_photo'],
                    'event_date' => $e['event_date'],
                    'is_active' => true,
                    'created_by' => $admin->id,
                ]
            );
        }

        // ---------- Sample booking + reviews ----------
        $booking = Booking::firstOrCreate(
            ['user_id' => $member->id, 'package_id' => $bromo->id, 'trip_date' => now()->addDays(30)->toDateString()],
            [
                'package_plan_id' => $bromo->plans()->first()?->id,
                'guest_name' => $member->name,
                'guest_email' => $member->email,
                'guest_phone' => $member->phone,
                'total_price' => 80,
                'status' => 'confirmed',
                'payment_method' => 'whatsapp',
                'payment_status' => 'paid',
            ]
        );

        $reviews = [
            ['name' => 'Robert Longstang', 'rating' => 5, 'comment' => 'The Bromo sunrise was incredible, the Overlander team was super friendly and on time!', 'destination' => 'Mount Bromo'],
            ['name' => 'Naibort Silalahi', 'rating' => 5, 'comment' => 'The most memorable Borobudur trip, the guide really knew the history well.', 'destination' => 'Borobudur Temple'],
            ['name' => 'Amelia Putri', 'rating' => 4, 'comment' => "Ijen's blue fire gave me chills, worth it even with the night trek.", 'destination' => 'Ijen Crater'],
            ['name' => 'David Chen', 'rating' => 5, 'comment' => "The most exciting 7-day overland trip I've ever joined, the route covered everything.", 'destination' => null],
            ['name' => 'Sarah Mitchell', 'rating' => 5, 'comment' => 'Watched the sunset at Tanah Lot and it was every bit as magical as the photos — the whole cliffside glows gold right as the tide comes in.', 'destination' => 'Tanah Lot Temple'],
            ['name' => 'Marco Alessandri', 'rating' => 5, 'comment' => "Did the full 10-day Java-to-Bali overland and it was worth every hour in the van — ending the trip with sunset at Tanah Lot right after the Ijen blue fire trek felt like the perfect finale.", 'destination' => null],
            ['name' => 'Yuki Tanaka', 'rating' => 4, 'comment' => 'Only had 4 days but still managed to catch the Bromo sunrise before flying out of Surabaya — perfect route for a short trip.', 'destination' => null],
        ];
        foreach ($reviews as $r) {
            Review::firstOrCreate(
                ['reviewer_name' => $r['name'], 'comment' => $r['comment']],
                [
                    'destination_id' => $r['destination'] ? $destinations[$r['destination']]->id : null,
                    'booking_id' => $r['destination'] === 'Mount Bromo' ? $booking->id : null,
                    'rating' => $r['rating'],
                    'is_featured' => true,
                ]
            );
        }
    }
}
