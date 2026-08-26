<?php
/**
 * Language Initialization
 * Handles language switching and loading
 */

// Define available languages
$available_langs = ['en', 'sw'];

// Get language from URL parameter, session, or default to English
if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

$current_lang = $_SESSION['lang'];

// Load language file
$lang_file = __DIR__ . '/' . $current_lang . '.php';
if (file_exists($lang_file)) {
    require_once $lang_file;
} else {
    require_once __DIR__ . '/en.php';
}

// Helper function to get translated content from database
function t($key, $fallback = '') {
    global $lang, $conn, $current_lang;
    
    // First check if there's a translated version in the database settings
    // e.g., key 'hero_title' would check 'hero_title_en' or 'hero_title_sw' depending on language
    if (isset($conn) && $conn !== null) {
        $db_key = $key . '_' . $current_lang;
        $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $db_key);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $db_val = $row['setting_value'];
                if (!empty($db_val)) {
                    $stmt->close();
                    return $db_val;
                }
            }
            $stmt->close();
            
            // Also try the English version as fallback for translated content
            if ($current_lang !== 'en') {
                $en_key = $key . '_en';
                $stmt2 = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
                if ($stmt2) {
                    $stmt2->bind_param("s", $en_key);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    if ($row = $result2->fetch_assoc()) {
                        $db_val = $row['setting_value'];
                        if (!empty($db_val)) {
                            $stmt2->close();
                            return $db_val;
                        }
                    }
                    $stmt2->close();
                }
            }
        }
    }
    
    // Fall back to language file
    return isset($lang[$key]) ? $lang[$key] : $fallback;
}

// Get current language
function getCurrentLang() {
    return isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
}

// Get alternate language URL
function getLangUrl($lang_code) {
    $params = $_GET;
    $params['lang'] = $lang_code;
    return strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query($params);
}

// Get current page URL without lang parameter
function getCurrentPageUrl() {
    $params = $_GET;
    unset($params['lang']);
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    return $url;
}
?>