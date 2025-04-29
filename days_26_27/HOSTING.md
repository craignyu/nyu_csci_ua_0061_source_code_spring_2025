Hosting on GitHub Pages

1. Set up a GitHub repository using GitHub Desktop or GitHub Web, and push your code.
   Important: Make sure your repository contains an index.html at the root level.

2. Sign in to github.com.

3. Click on Repositories in the top bar.

4. Select your repository.

5. Navigate to Settings → Pages (left sidebar under "Code and Automation").

6. Under Branch, select:

    main branch
    /(root) folder

7. Click Save.

8 Wait a few minutes (up to 5 minutes). Refresh the page to find your GitHub Pages URL:

https://YOUR_GITHUB_USERNAME.github.io/REPOSITORY_NAME/




Setting Up a Custom Domain

1. Purchase a domain from a domain registrar (e.g., porkbun.com).

2. In your GitHub repository, go to Settings → Pages.

3. In the Custom Domain box, enter your full domain name (e.g., example.com) and click Save.
   This will create a CNAME file automatically in your repo.

4. Edit your DNS settings at your domain registrar. You have two options:

   - Option A: One-Click GitHub Setup

        - If your registrar offers a GitHub Pages integration, you can use it.
          Just follow the prompts that appear.

   - Option B: Manual DNS Configuration

        - Add the following records:

            A Records (for root domain)

            A   @ (or your full domain name)   185.199.108.153
            A   @ (or your full domain name)   185.199.109.153
            A   @ (or your full domain name)   185.199.110.153
            A   @ (or your full domain name)   185.199.111.153

            AAAA Records (optional, for IPv6)

            AAAA   @ (or your full domain name)   2606:50c0:8000::153
            AAAA   @ (or your full domain name)   2606:50c0:8001::153
            AAAA   @ (or your full domain name)   2606:50c0:8002::153
            AAAA   @ (or your full domain name)   2606:50c0:8003::153

            CNAME Record (for www subdomain)

            CNAME   www   YOUR_GITHUB_USERNAME.github.io

        - Important:
            - Some registrars may expect you to manually type your full domain name (example.com) instead of using @ when setting DNS records.

            - Delete any conflicting default DNS records if needed.

            - Only the root domain (@) should have A and AAAA records.

            - Only the www subdomain should use a CNAME.

5. Return to GitHub Pages settings and click Check DNS Settings.
   Note: DNS changes can take up to 30 minutes to propagate.
