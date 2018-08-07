module.exports = {
  title: 'stool',
  description: 'A fully featured Digitalocean installer and management tool optimized for Laravel',

  themeConfig: {
    // Assumes GitHub. Can also be a full GitLab url.
    repo: 'ben182/server-tool',
    // Customising the header label
    // Defaults to "GitHub"/"GitLab"/"Bitbucket" depending on `themeConfig.repo`
    repoLabel: 'Contribute!',

    docsDir: 'docs',

    // defaults to false, set to true to enable
    editLinks: true,
    // custom text for edit link. Defaults to "Edit this page"
    editLinkText: 'Help me improve this page!',

    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/' },
      { text: 'External', link: 'https://google.com' },
    ],

    sidebar: [
      '/installation',
      {
        title: 'Commands',
        collapsable: false,
        children: [
          '/commands/addvhost'
        ]
      },
    ]
  }
}