<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('page.vision_title') ?></h1>
    </div>
</div>

<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div style="max-width:900px; margin:0 auto;">
            <article class="srisai-article">
                <div class="srisai-article__content">

                    <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] === 'ta'): ?>
                    <!-- TAMIL CONTENT — edit this section to add Tamil -->
                    <p>We aim to offer non-profitable selfless charity and service to all who are in need of basic care, regardless of their religion and caste. Our wide range of offerings include medical assistance, shelter, food, clothes, education to poor/less fortunate children, encourage community development programs, aid physically challenged people - this and much more.</p>
                    <p>Rebuild the human dignity of the poor and marginalized through an empowerment process, Education, food security, Health Care, employment to unemployed and create opportunities for a sustainable society.</p>
                    <p>Fostering community development through sustainable projects that promote self-reliance, environmental stewardship, and economic empowerment.</p>
                    <p>Through our unwavering commitment to these principles, Sri Sai Mission Trust endeavors to create a more equitable, inclusive, and compassionate society where every individual has the opportunity to thrive and fulfill their potential.</p>
                    <?php else: ?>
                    <!-- ENGLISH CONTENT -->
                    <p>We aim to offer non-profitable selfless charity and service to all who are in need of basic care, regardless of their religion and caste. Our wide range of offerings include medical assistance, shelter, food, clothes, education to poor/less fortunate children, encourage community development programs, aid physically challenged people - this and much more.</p>
                    <p>Rebuild the human dignity of the poor and marginalized through an empowerment process, Education, food security, Health Care, employment to unemployed and create opportunities for a sustainable society.</p>
                    <p>Fostering community development through sustainable projects that promote self-reliance, environmental stewardship, and economic empowerment.</p>
                    <p>Through our unwavering commitment to these principles, Sri Sai Mission Trust endeavors to create a more equitable, inclusive, and compassionate society where every individual has the opportunity to thrive and fulfill their potential.</p>
                    <?php endif; ?>

                </div>
            </article>
        </div>
    </div>
</div>
