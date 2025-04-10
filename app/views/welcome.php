<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>S-PHP Framework | Modern PHP Learning Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        :root {
            --black: #000000;
            --white: #ffffff;
            --light-gray: #f8f8f8;
            --medium-gray: #e0e0e0;
            --dark-gray: #333333;
            --accent-gray: #666666;
            --error-red: #d72638;
        }

        body {
            background-color: var(--white);
            color: var(--dark-gray);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        .header {
            background-color: var(--white);
            border-bottom: 1px solid var(--medium-gray);
            padding: 1.25rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.03em;
            color: var(--black);
        }

        .nav {
            display: flex;
            gap: 2.5rem;
        }

        .nav-link {
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            position: relative;
            transition: color 0.2s;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -6px;
            left: 0;
            background-color: var(--black);
            transition: width 0.3s;
        }

        .nav-link:hover {
            color: var(--black);
        }

        .nav-link:hover:after {
            width: 100%;
        }

        .main {
            flex: 1;
        }

        .hero {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content {
            max-width: 540px;
        }

        .title {
            font-size: 3.25rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: var(--black);
            letter-spacing: -0.03em;
        }

        .subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            margin-bottom: 2rem;
            color: var(--accent-gray);
        }

        .hero-image {
            background-color: var(--light-gray);
            border-radius: 12px;
            width: 100%;
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-image svg {
            max-width: 80%;
            max-height: 80%;
        }

        .warning-section {
            background-color: var(--light-gray);
            padding: 4rem 2rem;
        }

        .warning-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .warning-box {
            background: var(--white);
            border-left: 4px solid var(--error-red);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .warning-title {
            display: flex;
            align-items: center;
            color: var(--error-red);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .warning-icon {
            width: 24px;
            height: 24px;
            margin-right: 0.75rem;
            fill: var(--error-red);
        }

        .features-section {
            padding: 5rem 2rem;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--black);
            margin-bottom: 1rem;
            letter-spacing: -0.03em;
        }

        .section-description {
            font-size: 1.1rem;
            color: var(--accent-gray);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid var(--medium-gray);
            border-radius: 10px;
            padding: 2.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            margin-bottom: 1.5rem;
        }

        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--black);
        }

        .feature-description {
            color: var(--accent-gray);
            line-height: 1.6;
        }

        .code-section {
            background-color: var(--black);
            padding: 5rem 2rem;
            color: var(--white);
        }

        .code-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .code-header {
            margin-bottom: 2.5rem;
        }

        .code-block {
            background: #121212;
            border-radius: 8px;
            padding: 2rem;
            overflow-x: auto;
            font-family: 'Fira Code', 'Courier New', monospace;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .code-comment {
            color: #888;
        }

        .string {
            color: #a5d6a7;
        }

        .keyword {
            color: #ef9a9a;
        }

        .function {
            color: #90caf9;
        }

        .cta-section {
            padding: 5rem 2rem;
            text-align: center;
            background-color: var(--light-gray);
        }

        .cta-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--black);
            letter-spacing: -0.03em;
        }

        .cta-description {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: var(--accent-gray);
        }

        .button {
            display: inline-block;
            padding: 0.9rem 2.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
        }

        .button-primary {
            background-color: var(--black);
            color: var(--white);
        }

        .button-primary:hover {
            background-color: #222;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .button-secondary {
            background-color: var(--white);
            color: var(--black);
            border: 1px solid var(--medium-gray);
            margin-left: 1rem;
        }

        .button-secondary:hover {
            background-color: var(--light-gray);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .footer {
            background-color: var(--black);
            color: var(--white);
            padding: 4rem 2rem 2rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
        }

        .footer-logo {
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            letter-spacing: -0.03em;
        }

        .footer-description {
            color: #999;
            margin-bottom: 1.5rem;
            max-width: 320px;
        }

        .footer-column h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--white);
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-link {
            color: #999;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--white);
        }

        .footer-bottom {
            border-top: 1px solid #333;
            margin-top: 3rem;
            padding-top: 2rem;
            text-align: center;
            color: #999;
            font-size: 0.9rem;
        }

        .version-tag {
            display: inline-block;
            background: #333;
            color: var(--white);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 0.75rem;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="#" class="logo">
                <div class="logo-text">S-PHP</div>
                <div class="version-tag">v1.0.0</div>
            </a>
            <nav class="nav">
                <a href="#" class="nav-link">Home</a>
                <a href="https://docs-delta-amber.vercel.app/introduction/what-is-sphp.html" target="_blank" class="nav-link">Documentation</a>
                <a href="https://github.com/PranabZz/S-PHP" target="_blank" class="nav-link">GitHub</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <section class="hero">
            <div class="hero-content">
                <h1 class="title">Modern PHP Learning Framework</h1>
                <p class="subtitle">S-PHP is a lightweight framework designed to help developers understand the fundamentals of modern web architecture.</p>
                <div>
                    <a href="https://github.com/PranabZz/S-PHP" class="button button-primary">Get Started</a>
                    <a href="https://docs-delta-amber.vercel.app/introduction/what-is-sphp.html" class="button button-secondary">Documentation</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="/public/img/logo.png" alt="">
            </div>
        </section>

        <section class="warning-section">
            <div class="warning-container">
                <div class="warning-box">
                    <div class="warning-title">
                        <svg class="warning-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5.99L19.53 19H4.47L12 5.99ZM12 2L1 21h22L12 2Zm1 14h-2v2h2v-2Zm0-6h-2v4h2v-4Z"/>
                        </svg>
                        <span>Production Usage Warning</span>
                    </div>
                    <p>Are you considering using S-PHP in production? <strong>Please be careful!</strong> This framework is specifically designed as a learning tool to understand the basic principles of backend development and web architecture. It lacks many security features, optimizations, and robust error handling required for production environments.</p>
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="section-header">
                <h2 class="section-title">Key Features</h2>
                <p class="section-description">Discover the essential components that make S-PHP an ideal learning platform for modern web development.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 4L4 14V34L24 44L44 34V14L24 4Z" stroke="black" stroke-width="2"/>
                        <path d="M4 14L24 24M24 24L44 14M24 24V44" stroke="black" stroke-width="2"/>
                    </svg>
                    <h3 class="feature-title">MVC Architecture</h3>
                    <p class="feature-description">Learn the Model-View-Controller pattern through a clean, straightforward implementation that makes the concepts easy to understand.</p>
                </div>

                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 24H38" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10 15H38" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10 33H38" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <rect x="16" y="21" width="6" height="6" rx="3" fill="black"/>
                        <rect x="26" y="12" width="6" height="6" rx="3" fill="black"/>
                        <rect x="20" y="30" width="6" height="6" rx="3" fill="black"/>
                    </svg>
                    <h3 class="feature-title">Intuitive Routing</h3>
                    <p class="feature-description">Understand how modern web frameworks handle URL routing with our elegant and educational routing system.</p>
                </div>

                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="6" y="14" width="36" height="20" rx="2" stroke="black" stroke-width="2"/>
                        <path d="M14 14V10C14 7.79086 15.7909 6 18 6H30C32.2091 6 34 7.79086 34 10V14" stroke="black" stroke-width="2"/>
                        <path d="M14 34V38C14 40.2091 15.7909 42 18 42H30C32.2091 42 34 40.2091 34 38V34" stroke="black" stroke-width="2"/>
                        <circle cx="24" cy="24" r="4" fill="black"/>
                    </svg>
                    <h3 class="feature-title">Middleware Support</h3>
                    <p class="feature-description">Discover how middleware works to process requests and responses in a web application pipeline.</p>
                </div>

                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="12" width="32" height="24" rx="2" stroke="black" stroke-width="2"/>
                        <path d="M8 18H40" stroke="black" stroke-width="2"/>
                        <path d="M14 15H14.01" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18 15H18.01" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M22 15H22.01" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <rect x="12" y="22" width="10" height="10" rx="1" fill="black"/>
                        <path d="M26 23H36" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M26 27H36" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M26 31H36" stroke="black" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h3 class="feature-title">Basic ORM</h3>
                    <p class="feature-description">Explore a simplified Object-Relational Mapping implementation to understand database interactions.</p>
                </div>

                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 22C14 19.7909 15.7909 18 18 18H30C32.2091 18 34 19.7909 34 22V36C34 38.2091 32.2091 40 30 40H18C15.7909 40 14 38.2091 14 36V22Z" stroke="black" stroke-width="2"/>
                        <path d="M18 18V14C18 9.58172 21.5817 6 26 6C30.4183 6 34 9.58172 34 14V18" stroke="black" stroke-width="2"/>
                        <circle cx="24" cy="29" r="3" fill="black"/>
                        <path d="M24 32V35" stroke="black" stroke-width="2"/>
                    </svg>
                    <h3 class="feature-title">Authentication System</h3>
                    <p class="feature-description">Learn fundamental authentication concepts and implementation without the complexity.</p>
                </div>

                <div class="feature-card">
                    <svg class="feature-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 10C8 8.89543 8.89543 8 10 8H38C39.1046 8 40 8.89543 40 10V38C40 39.1046 39.1046 40 38 40H10C8.89543 40 8 39.1046 8 38V10Z" stroke="black" stroke-width="2"/>
                        <path d="M8 16H40" stroke="black" stroke-width="2"/>
                        <path d="M16 16V40" stroke="black" stroke-width="2"/>
                        <path d="M12 12H12.01" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16 12H16.01" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M20 12H20.01" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M22 20H34" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M22 24H34" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        <path d="M22 28H34" stroke="black" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h3 class="feature-title">Template Engine</h3>
                    <p class="feature-description">See how template engines work with our minimalist but functional templating system.</p>
                </div>
            </div>
        </section>

      
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <div class="footer-logo">S-PHP</div>
                <p class="footer-description">A lightweight PHP framework designed for educational purposes to help developers learn modern web development concepts.</p>
            </div>

            <div class="footer-column">
                <h3>Documentation</h3>
                <div class="footer-links">
                    <a href="https://docs-delta-amber.vercel.app/introduction/what-is-sphp.html" class="footer-link">Getting Started</a>
                  
                </div>
            </div>

            <div class="footer-column">
                <h3>Community</h3>
                <div class="footer-links">
                    <a href="https://github.com/PranabZz/S-PHP" class="footer-link">GitHub</a>
                  
                </div>
            </div>

            <div class="footer-column">
                <h3>Resources</h3>
                <div class="footer-links">
                  
                    <a href="#" class="footer-link">Report a Bug</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>S-PHP Framework © 2025 </p>
        </div>
    </footer>
</body>
</html>