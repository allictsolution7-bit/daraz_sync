<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h2>Welcome to Our Company</h2><p>We are a leading provider of innovative solutions in the technology industry. Our mission is to deliver exceptional products and services that exceed customer expectations.</p><h3>Our Story</h3><p>Founded in 2020, we have grown from a small startup to a recognized leader in our field. Our team of dedicated professionals works tirelessly to bring you the best solutions.</p><h3>Our Values</h3><ul><li>Innovation</li><li>Quality</li><li>Customer Satisfaction</li><li>Integrity</li></ul>',
                'status' => 1,
                'meta_title' => 'About Us - Learn About Our Company & Mission',
                'meta_description' => 'Discover our company story, mission, and values. Learn about our innovative solutions and dedicated team that delivers exceptional products and services.',
                'meta_keywords' => 'about us, company, mission, values, team, innovation, quality',
                'canonical_url' => 'https://example.com/about-us',
                'meta_robots' => 'index,follow',
                'og_image' => null,
                'og_image_alt' => 'About Us - Our Company Team and Mission',
                'schema_markup' => '{"@context":"https://schema.org","@type":"AboutPage","name":"About Us","description":"Learn about our company, mission, and values","url":"https://example.com/about-us","mainEntity":{"@type":"Organization","name":"Our Company","description":"Leading provider of innovative solutions"}}',
                'seo' => [
                    'meta_title' => 'About Us - Learn About Our Company & Mission',
                    'meta_description' => 'Discover our company story, mission, and values. Learn about our innovative solutions and dedicated team that delivers exceptional products and services.',
                    'meta_keywords' => 'about us, company, mission, values, team, innovation, quality',
                    'canonical_url' => 'https://example.com/about-us',
                    'meta_robots' => 'index,follow',
                    'og_image' => null,
                    'og_image_alt' => 'About Us - Our Company Team and Mission',
                    'schema_markup' => '{"@context":"https://schema.org","@type":"AboutPage","name":"About Us","description":"Learn about our company, mission, and values","url":"https://example.com/about-us","mainEntity":{"@type":"Organization","name":"Our Company","description":"Leading provider of innovative solutions"}}'
                ]
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p><h3>Information We Collect</h3><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p><h3>How We Use Your Information</h3><p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p><h3>Data Security</h3><p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>',
                'status' => 1,
                'meta_title' => 'Privacy Policy - How We Protect Your Data',
                'meta_description' => 'Read our comprehensive privacy policy to understand how we collect, use, and protect your personal information and data security practices.',
                'meta_keywords' => 'privacy policy, data protection, personal information, security, GDPR',
                'canonical_url' => 'https://example.com/privacy-policy',
                'meta_robots' => 'index,follow',
                'og_image' => null,
                'og_image_alt' => 'Privacy Policy - Data Protection and Security',
                'schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"Privacy Policy","description":"How we protect your data and personal information","url":"https://example.com/privacy-policy","isPartOf":{"@type":"WebSite","name":"Our Website"}}',
                'seo' => [
                    'meta_title' => 'Privacy Policy - How We Protect Your Data',
                    'meta_description' => 'Read our comprehensive privacy policy to understand how we collect, use, and protect your personal information and data security practices.',
                    'meta_keywords' => 'privacy policy, data protection, personal information, security, GDPR',
                    'canonical_url' => 'https://example.com/privacy-policy',
                    'meta_robots' => 'index,follow',
                    'og_image' => null,
                    'og_image_alt' => 'Privacy Policy - Data Protection and Security',
                    'schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"Privacy Policy","description":"How we protect your data and personal information","url":"https://example.com/privacy-policy","isPartOf":{"@type":"WebSite","name":"Our Website"}}'
                ]
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h2>Terms of Service</h2><p>These terms of service govern your use of our website and services. By using our services, you agree to these terms.</p><h3>Acceptance of Terms</h3><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h3>Use License</h3><p>Permission is granted to temporarily download one copy of the materials on our website for personal, non-commercial transitory viewing only.</p><h3>Disclaimer</h3><p>The materials on our website are provided on an "as is" basis. We make no warranties, expressed or implied, and hereby disclaim all other warranties.</p>',
                'status' => 1,
                'meta_title' => 'Terms of Service - Legal Terms & Conditions',
                'meta_description' => 'Read our terms of service to understand the legal terms and conditions for using our website and services.',
                'meta_keywords' => 'terms of service, legal terms, conditions, agreement, license',
                'canonical_url' => 'https://example.com/terms-of-service',
                'meta_robots' => 'index,follow',
                'og_image' => null,
                'og_image_alt' => 'Terms of Service - Legal Terms and Conditions',
                'schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"Terms of Service","description":"Legal terms and conditions for using our services","url":"https://example.com/terms-of-service","isPartOf":{"@type":"WebSite","name":"Our Website"}}',
                'seo' => [
                    'meta_title' => 'Terms of Service - Legal Terms & Conditions',
                    'meta_description' => 'Read our terms of service to understand the legal terms and conditions for using our website and services.',
                    'meta_keywords' => 'terms of service, legal terms, conditions, agreement, license',
                    'canonical_url' => 'https://example.com/terms-of-service',
                    'meta_robots' => 'index,follow',
                    'og_image' => null,
                    'og_image_alt' => 'Terms of Service - Legal Terms and Conditions',
                    'schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"Terms of Service","description":"Legal terms and conditions for using our services","url":"https://example.com/terms-of-service","isPartOf":{"@type":"WebSite","name":"Our Website"}}'
                ]
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '<h2>Get in Touch</h2><p>We would love to hear from you. Send us a message and we will respond as soon as possible.</p><h3>Contact Information</h3><p><strong>Address:</strong> 123 Business Street, City, State 12345</p><p><strong>Phone:</strong> (555) 123-4567</p><p><strong>Email:</strong> info@example.com</p><h3>Business Hours</h3><p>Monday - Friday: 9:00 AM - 6:00 PM</p><p>Saturday: 10:00 AM - 4:00 PM</p><p>Sunday: Closed</p><h3>Send us a Message</h3><p>Use our contact form to send us a message directly. We typically respond within 24 hours.</p>',
                'status' => 1,
                'meta_title' => 'Contact Us - Get in Touch with Our Team',
                'meta_description' => 'Contact us for support, questions, or inquiries. Find our contact information, business hours, and send us a message directly.',
                'meta_keywords' => 'contact us, support, customer service, get in touch, help',
                'canonical_url' => 'https://example.com/contact-us',
                'meta_robots' => 'index,follow',
                'og_image' => null,
                'og_image_alt' => 'Contact Us - Get in Touch with Our Team',
                'schema_markup' => '{"@context":"https://schema.org","@type":"ContactPage","name":"Contact Us","description":"Get in touch with our team","url":"https://example.com/contact-us","mainEntity":{"@type":"Organization","name":"Our Company","address":{"@type":"PostalAddress","streetAddress":"123 Business Street","addressLocality":"City","addressRegion":"State","postalCode":"12345","addressCountry":"US"},"telephone":"(555) 123-4567","email":"info@example.com"}}',
                'seo' => [
                    'meta_title' => 'Contact Us - Get in Touch with Our Team',
                    'meta_description' => 'Contact us for support, questions, or inquiries. Find our contact information, business hours, and send us a message directly.',
                    'meta_keywords' => 'contact us, support, customer service, get in touch, help',
                    'canonical_url' => 'https://example.com/contact-us',
                    'meta_robots' => 'index,follow',
                    'og_image' => null,
                    'og_image_alt' => 'Contact Us - Get in Touch with Our Team',
                    'schema_markup' => '{"@context":"https://schema.org","@type":"ContactPage","name":"Contact Us","description":"Get in touch with our team","url":"https://example.com/contact-us","mainEntity":{"@type":"Organization","name":"Our Company","address":{"@type":"PostalAddress","streetAddress":"123 Business Street","addressLocality":"City","addressRegion":"State","postalCode":"12345","addressCountry":"US"},"telephone":"(555) 123-4567","email":"info@example.com"}}'
                ]
            ],
            [
                'title' => 'FAQ',
                'slug' => 'frequently-asked-questions',
                'content' => '<h2>Frequently Asked Questions</h2><p>Find answers to the most common questions about our products and services.</p><h3>General Questions</h3><p><strong>Q: What is your return policy?</strong></p><p>A: We offer a 30-day return policy for all products in original condition.</p><p><strong>Q: How long does shipping take?</strong></p><p>A: Standard shipping takes 3-5 business days, while express shipping takes 1-2 business days.</p><h3>Technical Support</h3><p><strong>Q: How do I contact technical support?</strong></p><p>A: You can reach our technical support team via email at support@example.com or phone at (555) 123-4567.</p>',
                'status' => 1,
                'meta_title' => 'FAQ - Frequently Asked Questions & Answers',
                'meta_description' => 'Find answers to frequently asked questions about our products, services, shipping, returns, and technical support.',
                'meta_keywords' => 'FAQ, frequently asked questions, help, support, answers, shipping, returns',
                'canonical_url' => 'https://example.com/faq',
                'meta_robots' => 'index,follow',
                'og_image' => null,
                'og_image_alt' => 'FAQ - Frequently Asked Questions',
                'schema_markup' => '{"@context":"https://schema.org","@type":"FAQPage","name":"Frequently Asked Questions","description":"Common questions and answers about our products and services","url":"https://example.com/faq","mainEntity":[{"@type":"Question","name":"What is your return policy?","acceptedAnswer":{"@type":"Answer","text":"We offer a 30-day return policy for all products in original condition."}},{"@type":"Question","name":"How long does shipping take?","acceptedAnswer":{"@type":"Answer","text":"Standard shipping takes 3-5 business days, while express shipping takes 1-2 business days."}}]}',
                'seo' => [
                    'meta_title' => 'FAQ - Frequently Asked Questions & Answers',
                    'meta_description' => 'Find answers to frequently asked questions about our products, services, shipping, returns, and technical support.',
                    'meta_keywords' => 'FAQ, frequently asked questions, help, support, answers, shipping, returns',
                    'canonical_url' => 'https://example.com/faq',
                    'meta_robots' => 'index,follow',
                    'og_image' => null,
                    'og_image_alt' => 'FAQ - Frequently Asked Questions',
                    'schema_markup' => '{"@context":"https://schema.org","@type":"FAQPage","name":"Frequently Asked Questions","description":"Common questions and answers about our products and services","url":"https://example.com/faq","mainEntity":[{"@type":"Question","name":"What is your return policy?","acceptedAnswer":{"@type":"Answer","text":"We offer a 30-day return policy for all products in original condition."}},{"@type":"Question","name":"How long does shipping take?","acceptedAnswer":{"@type":"Answer","text":"Standard shipping takes 3-5 business days, while express shipping takes 1-2 business days."}}]}'
                ]
            ],
            [
                'title' => 'Return and Exchange Policy',
                'slug' => 'return-exchange',
                'content' => '<h2>Return and Exchange Policy</h2><p>We want you to be completely satisfied with your purchase. Here is our return and exchange policy.</p><h3>Return Policy</h3><p>You have 30 days from the date of purchase to return items for a full refund. Items must be in original condition with tags attached.</p><h3>Exchange Policy</h3><p>We offer free exchanges within 30 days of purchase. Simply contact us to initiate an exchange.</p><h3>How to Return</h3><p>1. Contact our customer service team</p><p>2. Receive a return authorization number</p><p>3. Package the item securely</p><p>4. Send it back to us</p>',
                'status' => 1,
                'meta_title' => 'Return & Exchange Policy - 30 Day Returns',
                'meta_description' => 'Learn about our return and exchange policy. 30-day returns, free exchanges, and easy return process for your peace of mind.',
                'meta_keywords' => 'return policy, exchange policy, refunds, 30 day returns, customer service',
                'canonical_url' => 'https://example.com/return-exchange',
                'meta_robots' => 'index,follow',
                'og_image' => null,
                'og_image_alt' => 'Return and Exchange Policy - 30 Day Returns',
                'schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"Return and Exchange Policy","description":"Our return and exchange policy for customer satisfaction","url":"https://example.com/return-exchange","isPartOf":{"@type":"WebSite","name":"Our Website"}}',
                'seo' => [
                    'meta_title' => 'Return & Exchange Policy - 30 Day Returns',
                    'meta_description' => 'Learn about our return and exchange policy. 30-day returns, free exchanges, and easy return process for your peace of mind.',
                    'meta_keywords' => 'return policy, exchange policy, refunds, 30 day returns, customer service',
                    'canonical_url' => 'https://example.com/return-exchange',
                    'meta_robots' => 'index,follow',
                    'og_image' => null,
                    'og_image_alt' => 'Return and Exchange Policy - 30 Day Returns',
                    'schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"Return and Exchange Policy","description":"Our return and exchange policy for customer satisfaction","url":"https://example.com/return-exchange","isPartOf":{"@type":"WebSite","name":"Our Website"}}'
                ]
            ]
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }

        $this->command->info('Created ' . count($pages) . ' demo pages with SEO data.');
    }
}