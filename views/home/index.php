
<div class="prayer-times-wrapper">
    <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="<?php echo \Utils\Env::base_url('/create'); ?>" class="subscribe-button">Create New Music Box</a>
        <?php if (!empty($data['boxes'])) { ?>
            <?php foreach ($data['boxes'] as $box) { ?>
                <div class="music-box" id="box-<?php echo $box['box_id']; ?>">
                    <header class="prayer-header">
                        <h1><?php echo htmlspecialchars($box['box_name']); ?></h1>
                        <p>Wilayah: <?php echo htmlspecialchars($box['prayerzone']); ?></p>
                    </header>
                    <div class="prayer-times">
                        <?php foreach ($box['prayers'] as $prayer) { ?>
                            <div class="prayer-time">
                            <?php
                            $prayerDate = DateTime::createFromFormat('Y-m-d', $prayer['prayertimedate']);
                            $formattedDate = $prayerDate ? $prayerDate->format('m-d') : '';

                            $prayerTime = DateTime::createFromFormat('H:i:s', $prayer['prayertime']);
                            $formattedTime = $prayerTime ? $prayerTime->format('H:i') : '';
                            ?>
<span><?php echo htmlspecialchars($prayer['song_title']); ?> (<?php echo $formattedDate; ?>) <?php echo $formattedTime; ?></span>

                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="subscribe-prompt">
                <h2>No Music Boxes Found</h2>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="subscribe-prompt">
            <h2>Subscribe to Create Your Music Box</h2>
            <p>Access personalized prayer times and more by creating your account.</p>
            <a href="<?php echo \Utils\Env::base_url('/subscriber'); ?>" class="subscribe-button">Sign Up</a>
        </div>
    <?php } ?>
</div>

<!-- Prayer Times JavaScript -->
<script type="text/javascript">
        var prayerTimesForBoxes = <?php echo json_encode(array_map(function ($box) {
            return [
                'boxName' => $box['box_name'],
                'prayerTimes' => array_map(function ($prayer) {
                    return [
                        'title' => $prayer['song_title'],
                        'time' => date('H:i', strtotime($prayer['prayertime'])),
                    ];
                }, $box['prayers']),
            ];
        }, $data['boxes'])); ?>;

        const ADHAN_DURATION_MS = 180000;

        function checkPrayerTimesAndPlayAdhan() {
            console.log('test');
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            prayerTimesForBoxes.forEach(box => {
            box.prayerTimes.forEach(prayer => {
                if (prayer.time === currentTime) {
                    // Determine which Adhan audio to use
                    var adhanAudioSrc = prayer.title.toLowerCase() === 'fajr' ? 'assets/audio/adhan_fajr.mp3' : 'assets/audio/adhan.mp3';
                    var adhanAudio = new Audio(adhanAudioSrc);

                    adhanAudio.play().then(() => {
                        // Highlight the box
                        const boxElement = document.getElementById('box-' + box.boxId);
                        if (boxElement) {
                            boxElement.classList.add('highlight');
                        }

                        // Remove the highlight after Adhan is finished
                        setTimeout(() => {
                            if (boxElement) {
                                boxElement.classList.remove('highlight');
                            }
                        }, ADHAN_DURATION_MS);
                    }).catch(e => {
                        console.error("Error playing Adhan: ", e);
                    });
                    console.log(`Playing Adhan for ${prayer.title} in ${box.boxName}`);
                }
            });
        });

        }

        setInterval(checkPrayerTimesAndPlayAdhan, 60000);

        function scheduleReloadAtMidnight() {
            const now = new Date();
            const target = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
            const timeUntilMidnight = target.getTime() - now.getTime();
            setTimeout(() => {
                window.location.reload();
            }, timeUntilMidnight);
        }

        scheduleReloadAtMidnight();
    </script>


