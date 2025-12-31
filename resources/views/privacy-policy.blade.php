<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Memora Photo Uploader</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.8;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #1a1a2e;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        
        .last-updated {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            border-left: 4px solid #60a5fa;
        }
        
        h2 {
            color: #1a1a2e;
            margin-top: 40px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #60a5fa;
            font-size: 1.8em;
        }
        
        h3 {
            color: #2d2d2d;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        
        p {
            margin-bottom: 15px;
            text-align: justify;
        }
        
        ul, ol {
            margin-left: 30px;
            margin-bottom: 20px;
        }
        
        li {
            margin-bottom: 10px;
        }
        
        .highlight {
            background: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #fbbf24;
            margin: 20px 0;
        }
        
        .important {
            background: #f8d7da;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #ef4444;
            margin: 20px 0;
        }
        
        .contact-info {
            background: #d1f4e0;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
            border-left: 4px solid #4ade80;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
        
        strong {
            color: #1a1a2e;
        }
        
        a {
            color: #60a5fa;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📸 Privacy Policy</h1>
        <p class="subtitle">Memora Photo Uploader</p>
        
        <div class="last-updated">
            <strong>Last Updated:</strong> December 31, 2024<br>
            <strong>Effective Date:</strong> December 31, 2024
        </div>

        <p>Thank you for using <strong>Memora Photo Uploader</strong> ("App"). We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, and protect your information when you use our App.</p>

        <div class="highlight">
            <strong>📌 Quick Summary:</strong>
            <ul>
                <li>We <strong>DO NOT collect</strong> your personal data</li>
                <li>We <strong>DO NOT use</strong> analytics or tracking</li>
                <li>We <strong>DO NOT display</strong> advertisements</li>
                <li>Photos you upload are sent to the server <strong>YOU specify</strong></li>
                <li>All data is stored <strong>locally</strong> on your device</li>
            </ul>
        </div>

        <h2>1. Information We Collect</h2>
        
        <h3>1.1 Locally Stored Information</h3>
        <p>The App stores the following information <strong>locally on your device</strong>:</p>
        <ul>
            <li><strong>API URL:</strong> The server address for photo uploads</li>
            <li><strong>Bearer Token:</strong> Authentication token for the API (if you provide one)</li>
            <li><strong>Session Code:</strong> Session identifier for uploads (if you provide one)</li>
            <li><strong>Upload Statistics:</strong> Number of photos uploaded, queue size, etc.</li>
            <li><strong>File History:</strong> List of files that have been processed</li>
        </ul>
        
        <p><strong>Important Note:</strong> This data is stored using Android <code>SharedPreferences</code> and is ONLY available on your device. We DO NOT have access to this data.</p>

        <h3>1.2 Photo Access</h3>
        <p>The App requires permission to:</p>
        <ul>
            <li><strong>READ_MEDIA_IMAGES</strong> (Android 13+) or <strong>READ_EXTERNAL_STORAGE</strong> (Android 12 and below)</li>
            <li>This permission is used ONLY to detect new photos in your gallery</li>
            <li>Photos are NOT copied or stored by the App</li>
            <li>Photos are directly uploaded to the server you specify</li>
        </ul>

        <h3>1.3 Information We DO NOT Collect</h3>
        <p>We <strong>DO NOT collect</strong>:</p>
        <ul>
            <li>Name, email, or other personal information</li>
            <li>Geographic location</li>
            <li>Contacts or friend lists</li>
            <li>Usage data or analytics</li>
            <li>IMEI, phone number, or other device identifiers</li>
            <li>Cookies or tracking data</li>
        </ul>

        <h2>2. How We Use Information</h2>
        
        <h3>2.1 Local Data Usage</h3>
        <p>Locally stored information is used for:</p>
        <ul>
            <li><strong>App Configuration:</strong> Remembering your API settings</li>
            <li><strong>Upload Tracking:</strong> Preventing duplicate uploads</li>
            <li><strong>Statistics:</strong> Displaying upload counts</li>
        </ul>

        <h3>2.2 Photo Uploads</h3>
        <p>When you enable monitoring:</p>
        <ul>
            <li>The App detects new photos in your gallery</li>
            <li>Photos are uploaded to <strong>the server YOU specify</strong> using the API URL you provide</li>
            <li>The App does NOT keep copies of photos</li>
            <li>The App does NOT send photos to our servers (because we don't have any servers)</li>
        </ul>

        <div class="important">
            <strong>⚠️ Important:</strong> We have NO control over the destination server you configure. That server's privacy policy may differ. Please ensure you trust the server you're using.
        </div>

        <h2>3. Information Sharing</h2>
        
        <p>We <strong>DO NOT share</strong> your information with third parties because:</p>
        <ul>
            <li>We don't collect your personal data</li>
            <li>All data is stored locally on your device</li>
            <li>The App doesn't use third-party analytics services</li>
            <li>The App doesn't use advertising networks</li>
        </ul>

        <h2>4. Data Security</h2>
        
        <h3>4.1 Local Storage</h3>
        <p>Data stored on your device is protected by:</p>
        <ul>
            <li>Android app sandbox (app isolation)</li>
            <li>Device encryption (if you enable it in Android settings)</li>
            <li>Access restricted to this App only</li>
        </ul>

        <h3>4.2 Data Transmission</h3>
        <p>During photo upload:</p>
        <ul>
            <li>Connection uses the protocol you specify (HTTP/HTTPS)</li>
            <li>We strongly recommend using <strong>HTTPS</strong> for security</li>
            <li>Bearer Token is sent via HTTP Authorization header</li>
        </ul>

        <div class="highlight">
            <strong>💡 Recommendation:</strong> Always use HTTPS for your API URL to ensure data is encrypted during transmission.
        </div>

        <h2>5. App Permissions</h2>
        
        <p>The App requires the following permissions:</p>
        
        <h3>5.1 Required Permissions</h3>
        <ul>
            <li><strong>INTERNET:</strong> To upload photos to your server</li>
            <li><strong>READ_MEDIA_IMAGES / READ_EXTERNAL_STORAGE:</strong> To detect new photos</li>
            <li><strong>FOREGROUND_SERVICE:</strong> For background monitoring</li>
            <li><strong>POST_NOTIFICATIONS:</strong> To display status notifications</li>
        </ul>

        <h3>5.2 Optional Permissions</h3>
        <ul>
            <li><strong>REQUEST_IGNORE_BATTERY_OPTIMIZATIONS:</strong> To prevent the app from being stopped when idle (you can deny this)</li>
            <li><strong>RECEIVE_BOOT_COMPLETED:</strong> For auto-start after reboot (if you enable it)</li>
        </ul>

        <h2>6. Children's Privacy</h2>
        
        <p>This App is <strong>NOT intended</strong> for children under the age of 13. We do not knowingly collect personal information from children.</p>
        
        <p>If you are a parent or guardian and become aware that your child has provided us with personal information, please contact us so we can take necessary action.</p>

        <h2>7. Your Rights</h2>
        
        <p>You have the right to:</p>
        <ul>
            <li><strong>Access Data:</strong> All data is stored on your device (Settings > Apps > Memora > Storage)</li>
            <li><strong>Delete Data:</strong> Use the "Reset" button in the app or uninstall the app</li>
            <li><strong>Revoke Permissions:</strong> Settings > Apps > Memora > Permissions</li>
            <li><strong>Stop Monitoring:</strong> Click the "Stop" button in the app</li>
        </ul>

        <h2>8. Data Retention and Deletion</h2>
        
        <h3>8.1 Retention Period</h3>
        <p>Data is stored on your device until:</p>
        <ul>
            <li>You delete data via the "Reset" button</li>
            <li>You uninstall the app</li>
            <li>You clear app data through Android settings</li>
        </ul>

        <h3>8.2 How to Delete Data</h3>
        <p>To delete all app data:</p>
        <ol>
            <li>Open Memora Photo Uploader app</li>
            <li>Click the "Reset" button</li>
            <li>Or: Settings > Apps > Memora Photo Uploader > Storage > Clear Data</li>
        </ol>

        <h2>9. Third-Party Services</h2>
        
        <p>This App <strong>DOES NOT use</strong> third-party services such as:</p>
        <ul>
            <li>Google Analytics</li>
            <li>Facebook SDK</li>
            <li>Advertising networks</li>
            <li>Crash reporting services</li>
            <li>Cloud storage services</li>
        </ul>

        <p>The only external connection is to <strong>the API server YOU specify</strong>.</p>

        <h2>10. Changes to Privacy Policy</h2>
        
        <p>We may update this Privacy Policy from time to time. Changes will be notified through:</p>
        <ul>
            <li>App updates on Google Play Store</li>
            <li>In-app notifications (for significant changes)</li>
            <li>This privacy policy page with updated "Last Updated" date</li>
        </ul>
        
        <p>We encourage you to review this Privacy Policy periodically.</p>

        <h2>11. Legal Basis (GDPR)</h2>
        
        <p>If you are in the European Union, our processing of your data is based on:</p>
        <ul>
            <li><strong>Consent:</strong> You grant gallery access permission during installation</li>
            <li><strong>Legitimate Interest:</strong> To provide the app functionality you requested</li>
        </ul>

        <h2>12. International Data Transfers</h2>
        
        <p>Since you specify the destination server yourself:</p>
        <ul>
            <li>Server location depends on the API URL you provide</li>
            <li>We have no control over the server location</li>
            <li>Ensure the server you use complies with regulations applicable in your region</li>
        </ul>

        <h2>13. Cookies and Tracking</h2>
        
        <p>This App <strong>DOES NOT use</strong>:</p>
        <ul>
            <li>Cookies</li>
            <li>Web beacons</li>
            <li>Tracking pixels</li>
            <li>Fingerprinting</li>
            <li>Analytics tracking</li>
        </ul>

        <h2>14. Contact Us</h2>
        
        <div class="contact-info">
            <p>If you have questions about this Privacy Policy, please contact us:</p>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:support@memora.my.id">support@memora.my.id</a></li>
                <li><strong>Developer:</strong> Memora Development Team</li>
            </ul>
            <p>We will respond to your inquiry within 48 hours.</p>
        </div>

        <h2>15. Consent</h2>
        
        <p>By using the Memora Photo Uploader app, you consent to the collection and use of information in accordance with this Privacy Policy.</p>
        
        <p><strong>Our Commitment:</strong></p>
        <ul>
            <li>✅ Full transparency about data usage</li>
            <li>✅ No hidden data collection</li>
            <li>✅ Your privacy is our top priority</li>
            <li>✅ Full control over your data</li>
        </ul>

        <div class="footer">
            <p>&copy; 2025 Memora Photo Uploader. All rights reserved.</p>
            <p>This Privacy Policy is created to comply with Google Play Store requirements and applicable privacy regulations.</p>
        </div>
    </div>
</body>
</html>