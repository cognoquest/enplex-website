<?php
// --- LANGUAGE CONFIGURATION ---
$lang = 'en'; // Default language
if (isset($_GET['lang']) && $_GET['lang'] == 'fr') {
    $lang = 'fr';
}

// All page text is stored in this array
$text = [
    'en' => [
        'page_title' => 'enplex.com - A Future-Forward Domain for Sale',
        'lang_switcher_text' => 'Version Française',
        'lang_switcher_link' => '?lang=fr',
        'tagline' => 'A short, tech-savvy domain name is available for acquisition.',
        'value_h2' => 'The Nexus of Modern Business',
        'value_p' => 'The name <strong>enplex</strong> combines "En-", suggesting enabling and engagement, with "-plex," representing a network or complex structure. It’s an invented, brandable name perfectly suited for a world of interconnected solutions. Ideal for SaaS, data analytics, logistics, or a forward-thinking consultancy.',
        'value_li1' => '<strong>Modern & Tech-Savvy:</strong> Sounds innovative and intelligent.',
        'value_li2' => '<strong>Highly Versatile:</strong> Perfect for software, consulting, or complex systems.',
        'value_li3' => '<strong>Short & Memorable:</strong> A six-letter `.com` is a rare and valuable digital asset.',
        'form_h2' => 'Make a Serious Inquiry',
        'form_label_name' => 'Your Name',
        'form_label_email' => 'Your Email',
        'form_label_offer' => 'Offer / Message',
        'form_submit_btn' => 'Submit Inquiry',
        'success_message' => 'Thank you for your inquiry. We have received your message and will be in touch shortly.',
        'error_general' => 'Please fill out all fields correctly.',
        'error_send' => 'Sorry, there was an error sending your message. Please try again later.'
    ],
    'fr' => [
        'page_title' => 'enplex.com - Domaine d\'Avenir à Vendre',
        'lang_switcher_text' => 'English Version',
        'lang_switcher_link' => 'index.php',
        'tagline' => 'Un nom de domaine court et technologique est disponible pour acquisition.',
        'value_h2' => 'Au Cœur de l\'Entreprise Moderne',
        'value_p' => 'Le nom <strong>enplex</strong> combine "En-", suggérant l\'activation et l\'engagement, avec "-plex," représentant un réseau ou une structure complexe. C\'est un nom inventé et mémorable, parfaitement adapté à un monde de solutions interconnectées. Idéal pour le SaaS, l\'analyse de données, la logistique ou une société de conseil innovante.',
        'value_li1' => '<strong>Moderne et Technologique:</strong> Une sonorité innovante et intelligente.',
        'value_li2' => '<strong>Extrêmement Polyvalent:</strong> Parfait pour les logiciels, le conseil ou les systèmes complexes.',
        'value_li3' => '<strong>Court et Mémorable:</strong> Un `.com` de six lettres est un actif numérique rare et précieux.',
        'form_h2' => 'Faire une Offre Sérieuse',
        'form_label_name' => 'Votre Nom',
        'form_label_email' => 'Votre Courriel',
        'form_label_offer' => 'Offre / Message',
        'form_submit_btn' => 'Soumettre la Demande',
        'success_message' => 'Merci pour votre demande. Nous avons bien reçu votre message et vous contacterons sous peu.',
        'error_general' => 'Veuillez remplir correctement tous les champs.',
        'error_send' => 'Désolé, une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.'
    ]
];

// --- FORM PROCESSING LOGIC ---
$message_sent = false;
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Honeypot check (if you've implemented it)
    if (!empty($_POST["website_url"])) {
        echo "Thank you for your submission.";
        exit();
    }

    // --- CAPTURE VISITOR IP ADDRESS ---
    $visitor_ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $visitor_ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $visitor_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $visitor_ip = $_SERVER['REMOTE_ADDR'];
    }

    // --- NEW: GET GEOLOCATION DATA FROM IP ---
    $location_info = "Location data not available."; // Default value
    // Check if the IP is a valid public IP before looking it up
    if (filter_var($visitor_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        // Use @file_get_contents to suppress warnings if the API call fails
        $geo_data_json = @file_get_contents("http://ip-api.com/json/" . $visitor_ip);
        if ($geo_data_json) {
            $geo_data = json_decode($geo_data_json);
            if ($geo_data && $geo_data->status == 'success') {
                $location_info = $geo_data->city . ", " . $geo_data->regionName . ", " . $geo_data->country;
            }
        }
    } else {
        $location_info = "Local or Private IP Address";
    }

    // --- IMPORTANT: CONFIGURE YOUR EMAIL HERE ---
    $your_email = "buy-enplex@cognoquest.com"; // <<<<<< CHANGE THIS!
    
    // Sanitize user input
    $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $offer = htmlspecialchars(strip_tags(trim($_POST["offer"])));

    // Validate input
    if (empty($name) || empty($offer) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = $text[$lang]['error_general'];
    } else {
        // Prepare email content
        // This example is for enplex.com, change the domain name for enplex.com
        $subject = "Inquiry for enplex.com from " . $name;
        $body = "You have received a new inquiry for the domain enplex.com.\n\n";
        $body .= "Name: " . $name . "\n";
        $body .= "Email: " . $email . "\n";
        $body .= "Offer/Message:\n" . $offer . "\n\n";
        $body .= "--- Technical Details ---\n";
        $body .= "Visitor IP: " . $visitor_ip . "\n";
        $body .= "Approx. Location: " . $location_info . "\n"; // <-- NEW LINE ADDED HERE
        
        $headers = "From: " . $name . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";

        if (mail($your_email, $subject, $body, $headers)) {
            $message_sent = true;
        } else {
            $error_message = $text[$lang]['error_send'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $text[$lang]['page_title']; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <nav class="lang-nav">
            <a href="<?php echo $text[$lang]['lang_switcher_link']; ?>"><?php echo $text[$lang]['lang_switcher_text']; ?></a>
        </nav>
        <header>
            <h1>enplex.com</h1>
            <p class="tagline"><?php echo $text[$lang]['tagline']; ?></p>
        </header>

        <main>
            <div class="value-proposition">
                <h2><?php echo $text[$lang]['value_h2']; ?></h2>
                <p><?php echo $text[$lang]['value_p']; ?></p>
                <ul>
                    <li><?php echo $text[$lang]['value_li1']; ?></li>
                    <li><?php echo $text[$lang]['value_li2']; ?></li>
                    <li><?php echo $text[$lang]['value_li3']; ?></li>
                </ul>
            </div>

            <div class="contact-form">
                <h2><?php echo $text[$lang]['form_h2']; ?></h2>
                
                <?php if ($message_sent): ?>
                    <div class="success-message">
                        <?php echo $text[$lang]['success_message']; ?>
                    </div>
                <?php else: ?>
                    <?php if ($error_message): ?>
                        <div class="error-message">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?lang=<?php echo $lang; ?>" method="post">
                        <div class="form-group">
                            <label for="name"><?php echo $text[$lang]['form_label_name']; ?></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><?php echo $text[$lang]['form_label_email']; ?></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="offer"><?php echo $text[$lang]['form_label_offer']; ?></label>
                            <textarea id="offer" name="offer" rows="5" required></textarea>
                        </div>
						<div class="honeypot">
							<label for="website_url">Website</label>
							<input type="text" id="website_url" name="website_url" autocomplete="off" tabindex="-1">
						</div>						
                        <button type="submit" class="submit-btn"><?php echo $text[$lang]['form_submit_btn']; ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </main>
        
        <footer>
            <p>Copyright &copy; <?php echo date("Y"); ?> enplex.com. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>