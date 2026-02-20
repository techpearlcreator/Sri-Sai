<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('page.mission_title') ?></h1>
    </div>
</div>

<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div style="max-width:900px; margin:0 auto;">
            <article class="srisai-article">
                <div class="srisai-article__content">

                    <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] === 'ta'): ?>
                    <!-- TAMIL CONTENT — edit this section to add Tamil -->
                    <p>Sri Sai Baba's Mission was to bring together all religions under a common fold, that Baba promoted a religion of love with peace and harmony and that Baba belonged to no particular religion or faith.</p>
                    <p>Sri Sai Mission Charitable Trust visualizes a society in which peace, justice, and equality prevail and strives to build an India where all people have access to education, healthcare, employment, housing &amp; sanitation and economic self-reliance, and where all Indians can realize their full potential offsetting barriers of caste, creed, color, language and gender.</p>
                    <p>Our Mission to create awareness among the fellow citizens towards the well being of old age people, woman empowerment, and helpless children. We also work to spread awareness about the seasonal diseases and deadly diseases like AIDS, cancer, etc.</p>
                    <?php else: ?>
                    <!-- ENGLISH CONTENT -->
                    <p>Sri Sai Baba's Mission was to bring together all religions under a common fold, that Baba promoted a religion of love with peace and harmony and that Baba belonged to no particular religion or faith.</p>
                    <p>Sri Sai Mission Charitable Trust visualizes a society in which peace, justice, and equality prevail and strives to build an India where all people have access to education, healthcare, employment, housing &amp; sanitation and economic self-reliance, and where all Indians can realize their full potential offsetting barriers of caste, creed, color, language and gender.</p>
                    <p>Our Mission to create awareness among the fellow citizens towards the well being of old age people, woman empowerment, and helpless children. We also work to spread awareness about the seasonal diseases and deadly diseases like AIDS, cancer, etc.</p>
                    <?php endif; ?>

                </div>
            </article>
        </div>
    </div>
</div>
