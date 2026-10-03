<?php

// Practical guide per destination: best months, what to bring, safety and (optionally) what we provide.
// One point per line. Shared by the add_guides migration (backfill) and DemoDataSeeder (fresh installs).
// "provided" is only filled where the operator really supplies the gear (Ijen); leave it out otherwise.
return [
    'mount-bromo' => [
        'months' => [4, 5, 6, 7, 8, 9, 10],
        'best_time' => [
            'en' => "Dry season, roughly April to October, gives the clearest sunrise. June to August is usually the most reliable.\nJanuary to March is the rainy season: expect cloud, mud and fewer clear mornings.\nWeekends and school holidays bring the biggest crowds at the viewpoint.",
            'id' => "Musim kemarau, kira-kira April sampai Oktober, kasih sunrise paling jelas. Juni sampai Agustus biasanya paling bisa diandalkan.\nJanuari sampai Maret musim hujan: sering berawan, berlumpur, dan lebih jarang cerah.\nAkhir pekan dan libur sekolah bikin viewpoint paling ramai.",
        ],
        'packing' => [
            'en' => "Warm jacket, gloves and a beanie: it is near freezing before dawn\nMask or buff for dust and sulfur smoke\nSturdy closed shoes for loose sand and steep crater stairs\nSmall flashlight or headlamp for the early start\nWater, a snack and a power bank (cold drains batteries fast)",
            'id' => "Jaket tebal, sarung tangan dan kupluk: menjelang subuh dinginnya hampir beku\nMasker atau buff buat debu dan asap belerang\nSepatu tertutup yang kuat buat pasir gembur dan tangga kawah yang curam\nSenter kecil atau headlamp buat berangkat dini hari\nAir minum, camilan dan power bank (dingin bikin baterai cepat habis)",
        ],
        'safety' => [
            'en' => "Stay behind the railings and away from the crater edge, especially when it is dark or windy\nSulfur fumes can be strong: cover your nose and mouth and move away if it becomes hard to breathe\nLayer your clothes; the temperature swings a lot between dawn and midday\nStay with your group and guide on the sand sea, visibility can drop quickly in fog",
            'id' => "Tetap di balik pagar dan jauhi bibir kawah, terutama saat gelap atau berangin\nAsap belerang bisa pekat: tutup hidung dan mulut, lalu menjauh kalau jadi sulit bernapas\nPakai pakaian berlapis; suhunya berubah drastis dari subuh ke tengah hari\nTetap bersama rombongan dan pemandu di lautan pasir, jarak pandang bisa cepat turun saat berkabut",
        ],
    ],

    'borobudur-temple' => [
        'months' => [4, 5, 6, 7, 8, 9, 10],
        'best_time' => [
            'en' => "Dry season, April to October, is the most comfortable. Go at opening time or sunrise to avoid heat and crowds.\nThe busiest days are national holidays, long weekends and Waisak (usually May or June).\nIn the rainy season the stone steps are slippery, so take care.",
            'id' => "Musim kemarau, April sampai Oktober, paling nyaman. Datang pas jam buka atau sunrise biar nggak kepanasan dan nggak ramai.\nHari paling padat adalah libur nasional, akhir pekan panjang dan Waisak (biasanya Mei atau Juni).\nPas musim hujan anak tangga batu licin, jadi hati-hati.",
        ],
        'packing' => [
            'en' => "Modest clothes that cover shoulders and knees\nHat and sunscreen: there is almost no shade on the terraces\nWater bottle, the stone gets very hot by midday\nComfortable shoes with grip (special footwear may be required for the upper terraces)\nA small bag only, large bags may not be allowed in",
            'id' => "Pakaian sopan yang menutup bahu dan lutut\nTopi dan tabir surya: di teras hampir nggak ada tempat teduh\nBotol minum, batunya panas banget saat tengah hari\nSepatu nyaman yang cengkeramannya bagus (alas kaki khusus bisa diwajibkan untuk teras atas)\nBawa tas kecil saja, tas besar mungkin tidak boleh masuk",
        ],
        'safety' => [
            'en' => "Follow the rangers' instructions and the daily visitor quota for the upper terraces\nDo not climb on or sit on the stupas and do not touch the reliefs\nDrink water often and rest in the shade; heat exhaustion is the most common problem\nWatch your step on the steep, worn stone stairs",
            'id' => "Ikuti arahan petugas dan kuota harian pengunjung untuk teras atas\nJangan naik atau duduk di atas stupa dan jangan menyentuh relief\nMinum air sering-sering dan istirahat di tempat teduh; kelelahan karena panas paling sering terjadi\nHati-hati melangkah di tangga batu yang curam dan sudah aus",
        ],
    ],

    'ijen-crater' => [
        'months' => [4, 5, 6, 7, 8, 9, 10],
        'best_time' => [
            'en' => "Dry season, April to October, gives the best chance of clear skies and a visible blue fire.\nIn the rainy season the trail is slippery and mist can hide the fire; the crater can also close for safety.\nThe hike starts around 1 to 2 AM all year, so the blue fire is only visible in the dark.",
            'id' => "Musim kemarau, April sampai Oktober, peluang terbaik buat langit cerah dan blue fire kelihatan.\nPas musim hujan jalurnya licin dan kabut bisa menutupi api; kawah juga bisa ditutup demi keselamatan.\nPendakian dimulai sekitar jam 1 sampai 2 pagi sepanjang tahun, karena blue fire cuma kelihatan dalam gelap.",
        ],
        'packing' => [
            'en' => "Warm jacket, gloves and a beanie: it is cold at the summit\nTrekking shoes with good grip for the steep gravel trail\nWater and a light snack\nPower bank and a spare layer for the wait at the rim\nA waterproof bag for your camera if the weather turns",
            'id' => "Jaket tebal, sarung tangan dan kupluk: di puncak dingin\nSepatu trekking dengan cengkeraman bagus buat jalur kerikil yang curam\nAir minum dan camilan ringan\nPower bank dan satu lapis pakaian cadangan buat menunggu di bibir kawah\nTas anti air buat kamera kalau cuaca berubah",
        ],
        'provided' => [
            'en' => "Headlamp\nTrekking stick\nGas mask",
            'id' => "Headlamp\nTongkat pendakian\nMasker gas",
        ],
        'safety' => [
            'en' => "Wear the gas mask we provide whenever your guide tells you to; the sulfur fumes can be very strong\nWhen the wind blows fumes toward you, follow your guide and stay away from the crater edge\nNever go down toward the blue fire without your guide: the path is steep, rocky and the gas is concentrated there\nIf you have asthma or heart problems, tell your guide before you start and take it slowly\nStay on the trail and keep your headlamp on in the dark",
            'id' => "Pakai masker gas dari kami setiap kali pemandu memintanya; asap belerang bisa sangat pekat\nKalau angin meniup asap ke arahmu, ikuti pemandu dan jauhi bibir kawah\nJangan pernah turun mendekati blue fire tanpa pemandu: jalurnya curam, berbatu, dan gasnya terkonsentrasi di sana\nKalau punya asma atau masalah jantung, kasih tahu pemandu sebelum mulai dan jalannya pelan-pelan\nTetap di jalur dan nyalakan headlamp saat gelap",
        ],
    ],

    'tumpak-sewu-waterfall' => [
        'months' => [5, 6, 7, 8, 9, 10],
        'best_time' => [
            'en' => "Dry season, roughly May to October, is the safest and clearest time to visit.\nIn the rainy season the trail is muddy and slippery and the falls can turn brown.\nMornings give the best light and fewer people.",
            'id' => "Musim kemarau, kira-kira Mei sampai Oktober, waktu paling aman dan paling jernih buat berkunjung.\nPas musim hujan jalurnya berlumpur dan licin, air terjun bisa jadi cokelat.\nPagi hari cahayanya paling bagus dan lebih sepi.",
        ],
        'packing' => [
            'en' => "Shoes or sandals with strong grip (wet rock and mud)\nWaterproof bag or dry bag for your phone and camera\nQuick-dry clothes and a full change of clothes\nSmall towel and a light rain jacket\nWater, the climb back up is tiring",
            'id' => "Sepatu atau sandal yang cengkeramannya kuat (batu basah dan lumpur)\nTas anti air atau dry bag buat HP dan kamera\nPakaian cepat kering dan baju ganti lengkap\nHanduk kecil dan jas hujan tipis\nAir minum, jalan naik baliknya capek",
        ],
        'safety' => [
            'en' => "The way down has steep stairs and ladders: use the handrails and go one at a time\nDo not go to the base after heavy rain, the water level can rise quickly\nAvoid swimming near the falls; the current and falling water are stronger than they look\nKeep the trail clear and watch your step on wet stone",
            'id' => "Jalan turun punya tangga curam dan tangga besi: pegang pegangan tangan dan turun satu per satu\nJangan turun ke dasar setelah hujan deras, debit air bisa naik cepat\nHindari berenang di dekat air terjun; arus dan air jatuhnya lebih kuat dari kelihatannya\nJangan menghalangi jalur dan hati-hati melangkah di batu basah",
        ],
    ],

    'malioboro-yogyakarta-palace' => [
        'months' => [4, 5, 6, 7, 8, 9, 10],
        'best_time' => [
            'en' => "Yogyakarta can be visited all year; April to October is driest and most comfortable for walking.\nMalioboro is liveliest from late afternoon into the evening.\nThe Kraton is best in the morning and closes in the early afternoon.",
            'id' => "Yogyakarta bisa dikunjungi sepanjang tahun; April sampai Oktober paling kering dan nyaman buat jalan kaki.\nMalioboro paling ramai dari sore menjelang malam.\nKraton paling enak dikunjungi pagi dan tutup di awal siang.",
        ],
        'packing' => [
            'en' => "Comfortable walking shoes or sandals\nLight clothes and a hat for the heat\nSmall cash for street food, becak and souvenirs\nPower bank and a small bag you can keep in front of you\nModest clothes for the Kraton (shoulders and knees covered)",
            'id' => "Sepatu atau sandal jalan yang nyaman\nPakaian ringan dan topi buat cuaca panas\nUang tunai pecahan kecil buat jajan, becak dan oleh-oleh\nPower bank dan tas kecil yang bisa dibawa di depan badan\nPakaian sopan buat Kraton (bahu dan lutut tertutup)",
        ],
        'safety' => [
            'en' => "Keep your phone and wallet in a front pocket or zipped bag; pickpockets work the crowds\nAgree on the fare before taking a becak or andong\nCheck prices before ordering at street stalls\nWatch for motorbikes on the pedestrian paths, especially in the evening",
            'id' => "Simpan HP dan dompet di saku depan atau tas yang diresleting; pencopet beraksi di keramaian\nSepakati tarif dulu sebelum naik becak atau andong\nCek harga dulu sebelum pesan di warung kaki lima\nWaspadai motor yang lewat di jalur pejalan kaki, terutama malam hari",
        ],
    ],

    'papuma-beach' => [
        'months' => [4, 5, 6, 7, 8, 9, 10],
        'best_time' => [
            'en' => "Dry season, April to October, gives the best weather and the most colourful sunsets.\nIn the rainy season the sea is rougher and the skies are often grey.\nAfternoons into sunset are the nicest hours, and the beach is quieter on weekdays.",
            'id' => "Musim kemarau, April sampai Oktober, kasih cuaca terbaik dan sunset paling berwarna.\nPas musim hujan lautnya lebih ganas dan langit sering mendung.\nSore menjelang sunset adalah jam terbaik, dan pantai lebih sepi di hari kerja.",
        ],
        'packing' => [
            'en' => "Sunscreen, hat and sunglasses: little shade on the sand\nSandals or shoes with grip for the rocks\nPlenty of water\nTowel and a change of clothes\nCamera or phone with a waterproof pouch",
            'id' => "Tabir surya, topi dan kacamata hitam: di pasir hampir nggak ada tempat teduh\nSandal atau sepatu dengan cengkeraman bagus buat batu karang\nAir minum yang banyak\nHanduk dan baju ganti\nKamera atau HP dengan pouch anti air",
        ],
        'safety' => [
            'en' => "The south coast has strong waves and currents: do not swim far from shore and follow the warning signs\nThe rock outcrops are slippery, and waves can reach them without warning, so keep your distance at high tide\nKeep an eye on children near the water",
            'id' => "Pantai selatan ombak dan arusnya kuat: jangan berenang jauh dari tepi dan ikuti rambu peringatan\nBatu karangnya licin, dan ombak bisa menjangkaunya tiba-tiba, jadi jaga jarak saat air pasang\nAwasi anak-anak di dekat air",
        ],
    ],

    'tanah-lot-temple' => [
        'months' => [4, 5, 6, 7, 8, 9, 10],
        'best_time' => [
            'en' => "Bali's dry season, April to October, gives the clearest sunsets.\nJuly, August and the December holidays are the busiest; arrive early for a good spot.\nThe temple rock can only be reached at low tide, so check the tide times.",
            'id' => "Musim kemarau Bali, April sampai Oktober, kasih sunset paling jelas.\nJuli, Agustus dan libur Desember paling ramai; datang lebih awal biar dapat tempat bagus.\nBatu candi cuma bisa dicapai saat air surut, jadi cek jadwal pasang surut.",
        ],
        'packing' => [
            'en' => "Sarong or sash for the temple area (often available to borrow or rent at the entrance)\nSunscreen and a hat for the wait before sunset\nCash for the entrance fee, snacks and the market\nComfortable shoes or sandals for steps and wet stone\nCamera, the sunset silhouette is the highlight",
            'id' => "Kain atau selendang buat area pura (biasanya bisa dipinjam atau disewa di pintu masuk)\nTabir surya dan topi buat menunggu sunset\nUang tunai buat tiket masuk, jajan dan pasar\nSepatu atau sandal nyaman buat tangga dan batu basah\nKamera, siluet sunset adalah sorotan utamanya",
        ],
        'safety' => [
            'en' => "Only cross to the rock when the tide is low and the staff allow it; the water rises quickly\nThe rocks and steps are slippery, so go slowly and hold the railings\nThe inner temple is for worshippers only, enjoy it from outside\nThe viewpoints get very crowded at sunset, so mind your belongings and the cliff edges",
            'id' => "Menyeberang ke batu hanya saat air surut dan petugas mengizinkan; air naik dengan cepat\nBatu dan anak tangganya licin, jadi pelan-pelan dan pegang pagar\nBagian dalam pura khusus umat yang beribadah, nikmati dari luar\nViewpoint sangat padat saat sunset, jadi jaga barang bawaan dan tepi tebing",
        ],
    ],
];
