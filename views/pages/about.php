<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('page.about_title') ?></h1>
    </div>
</div>

<!-- About Us Content -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div style="max-width:900px; margin:0 auto;">
            <article class="srisai-article">
                <div class="srisai-article__content">

                    <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] === 'ta'): ?>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAMIL CONTENT — Edit below to update Tamil version  -->
                    <!-- ═══════════════════════════════════════════════════ -->
                                    <h2>ஸ்ரீ சாய் மிஷன் பற்றி</h2>
                    <p>ஸ்ரீ சாய் மிஷன் மத மற்றும் அறக்கட்டளை (பதிவு) 106/2014, சென்னை — அன்னதானம், கோசாலை, கோவில் பூஜை மற்றும் சமூக நல செயல்பாடுகள் மூலம் மனிதகுலத்திற்கு சேவை செய்ய அர்ப்பணிக்கப்பட்ட ஒரு ஆன்மீக அமைப்பு.</p>

                    <h3>எங்கள் நோக்கம் / எங்கள் நோக்கம்</h3>
                    <p>ஷிர்டி சாய் பாபாவின் தெய்வீக போதனைகளை பரப்பி, பொருளாதார ரீதியாக பின்தங்கிய மக்களை உயர்த்தவும் ஆன்மீக வளர்ச்சியை ஊக்குவிக்கவும் அறப்பணிகள் மூலம் சமூகத்திற்கு சேவை செய்வது.</p>

                    <h3>எங்கள் பணி / எங்கள் நோக்கம்</h3>
                    <p>தினசரி கோவில் பூஜைகள் நடத்துதல், தேவையுள்ளவர்களுக்கு இலவச அன்னதானம் வழங்குதல், கோசாலை பராமரித்தல், ஆன்மீக நிகழ்ச்சிகள் ஏற்பாடு செய்தல் மற்றும் சாய் பாபாவின் போதனைகளை பகிர்ந்து கொள்ள ஸ்ரீ சாய் தரிசனம் மாத இதழை வெளியிடுதல்.</p>

                    <h3>எங்கள் கோயில்கள் / எங்கள் கோயில்கள்</h3>
                    <p><strong>பெருங்களத்தூர் ஆத்மா சாய் கோவில்</strong> — சென்னை பெருங்களத்தூரில் அமைந்துள்ள எங்கள் முதன்மை கோவில்; இங்கு தினசரி பூஜைகளும் சிறப்பு நிகழ்ச்சிகளும் நடைபெறுகின்றன.</p>
                    <p><strong>கீரப்பாக்கம் பாபா கோவில்</strong> — கீரப்பாக்கத்தில் அமைந்துள்ள எங்கள் இரண்டாவது கோவில்; ஷிர்டி சாய் பாபாவிற்கு அர்ப்பணிக்கப்பட்டது.</p>

                    <h3>எங்கள் செயல்பாடுகள் / எங்கள் செயல்பாடுகள்</h3>
                    <ul>
                        <li><strong>அன்னதானம்</strong> — பக்தர்களுக்கும் தேவையுள்ளவர்களுக்கும் இலவச உணவு வழங்குதல்</li>
                        <li><strong>கோசாலை</strong> — புனித சேவையாக பசுக்களை பராமரித்தல் மற்றும் பாதுகாத்தல்</li>
                        <li><strong>கோவில் பூஜை</strong> — தினசரி வழிபாடு மற்றும் திருவிழா கொண்டாட்டங்கள்</li>
                        <li><strong>நிகழ்ச்சிகள் &amp; யாத்திரை</strong> — தீர்த்தயாத்திரை சுற்றுப்பயணங்கள் மற்றும் ஆன்மீக கூடல்கள்</li>
                        <li><strong>இதழ்</strong> — ஸ்ரீ சாய் தரிசனம் மாத இதழ் வெளியீடு</li>
                    </ul>
                    <?php else: ?>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- ENGLISH CONTENT — Edit below to update English version -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <h2>About Sri Sai Mission</h2>
                    <p>Sri Sai Mission Religious &amp; Charitable Trust (Regd) 106/2014, Chennai, is a spiritual organization dedicated to serving humanity through Annadhanam, Cow Saala, Temple Worship, and community welfare activities.</p>

                    <p>Sri Sai Mission Charitable Trust is a Non-Profitable Organization dedicated for helping needy and neglected Men, Women &amp; Children. It has been working in India since 2014. The main object being propagation of the Life and Teachings of Sri Sai Baba of Shirdi.</p>

                    <p>Sri Sai Mission Charitable Trust was founded by Sri Sai Varadharajan, a pious Sai Devotee, philanthropist, writer and journalist of Chennai. It is a Public Charitable Trust under Income Tax act Section 10(23c) iv of the Income Tax act 1961 for carrying out activities in the areas of basic education, health care, Helping in Disaster, Skill Development, intervention, treatment, Care and lot more objects of general public Utility.</p>

                    <p>The Trust has been granted recognition for Tax Exemption under section 80G of the Income Tax.</p>

                    <h3>Our Temples</h3>
                    <p><strong>Perungalathur Athma Sai Temple</strong> — Our main temple in Perungalathur, Chennai, where daily worship and special events are conducted.</p>
                    <p><strong>Keerapakkam Baba Temple</strong> — Our second temple located in Keerapakkam, dedicated to Shirdi Sai Baba.</p>

                    <h3>Our Activities</h3>
                    <ul>
                        <li><strong>Annadhanam</strong> — Free food distribution to devotees and the needy</li>
                        <li><strong>Cow Saala</strong> — Maintenance and care of cows as a sacred service</li>
                        <li><strong>Temple Pooja</strong> — Daily worship and special festival celebrations</li>
                        <li><strong>Events &amp; Tourism</strong> — Pilgrimage tours and spiritual gatherings</li>
                        <li><strong>Magazine</strong> — Sri Sai Dharisanam monthly publication</li>
                    </ul>

                    <?php endif; ?>

                </div>
            </article>
        </div>
    </div>
</div>
