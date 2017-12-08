
<?php

class Pagarme_Notifications_Helper_Data_Test extends \PHPUnit_Framework_TestCase
{
    private $helper;

    protected function setUp()
    {
        $this->helper = new Pagarme_Notifications_Helper_Data();
    }

    public function testVersionFilter()
    {
        $latestVersion = $this->helper->getLatestVersionFor('master',
            array(
                array(
                    "url"=> "https://api.github.com/repos/pagarme/pagarme-magento/releases/6141746",
                    "assets_url"=> "https://api.github.com/repos/pagarme/pagarme-magento/releases/6141746/assets",
                    "upload_url"=> "https://uploads.github.com/repos/pagarme/pagarme-magento/releases/6141746/assets{?name,label}",
                    "html_url"=> "https://github.com/pagarme/pagarme-magento/releases/tag/v2.0.0",
                    "id"=> 6141746,
                    "tag_name"=> "v2.0.0",
                    "target_commitish"=> "v2",
                    "name"=> "v2.0.0",
                    "draft"=> false,
                    "author"=> array(
                        "login"=> "devdrops",
                        "id"=> 1259313,
                        "avatar_url"=> "https://avatars2.githubusercontent.com/u/1259313?v=4",
                        "gravatar_id"=> "",
                        "url"=> "https://api.github.com/users/devdrops",
                        "html_url"=> "https://github.com/devdrops",
                        "followers_url"=> "https://api.github.com/users/devdrops/followers",
                        "following_url"=> "https://api.github.com/users/devdrops/following{/other_user}",
                        "gists_url"=> "https://api.github.com/users/devdrops/gists{/gist_id}",
                        "starred_url"=> "https://api.github.com/users/devdrops/starred{/owner}{/repo}",
                        "subscriptions_url"=> "https://api.github.com/users/devdrops/subscriptions",
                        "organizations_url"=> "https://api.github.com/users/devdrops/orgs",
                        "repos_url"=> "https://api.github.com/users/devdrops/repos",
                        "events_url"=> "https://api.github.com/users/devdrops/events{/privacy}",
                        "received_events_url"=> "https://api.github.com/users/devdrops/received_events",
                        "type"=> "User",
                        "site_admin"=> false
                    ),
                    "prerelease"=> false,
                    "created_at"=> "2017-06-01T14:11:46Z",
                    "published_at"=> "2017-06-01T15:38:47Z",
                    "assets"=> [],
                    "tarball_url"=> "https://api.github.com/repos/pagarme/pagarme-magento/tarball/v2.0.0",
                    "zipball_url"=> "https://api.github.com/repos/pagarme/pagarme-magento/zipball/v2.0.0",
                    "body"=> "Nova versão do módulo para Magento 1.x (> 1.7)"
                ),
                array(
                    "url"=> "https://api.github.com/repos/pagarme/pagarme-magento/releases/6141746",
                    "assets_url"=> "https://api.github.com/repos/pagarme/pagarme-magento/releases/6141746/assets",
                    "upload_url"=> "https://uploads.github.com/repos/pagarme/pagarme-magento/releases/6141746/assets{?name,label}",
                    "html_url"=> "https://github.com/pagarme/pagarme-magento/releases/tag/v2.0.0",
                    "id"=> 6141746,
                    "tag_name"=> "v2.0.0",
                    "target_commitish"=> "master",
                    "name"=> "v0.1.6",
                    "draft"=> false,
                    "author"=> array(
                        "login"=> "devdrops",
                        "id"=> 1259313,
                        "avatar_url"=> "https://avatars2.githubusercontent.com/u/1259313?v=4",
                        "gravatar_id"=> "",
                        "url"=> "https://api.github.com/users/devdrops",
                        "html_url"=> "https://github.com/devdrops",
                        "followers_url"=> "https://api.github.com/users/devdrops/followers",
                        "following_url"=> "https://api.github.com/users/devdrops/following{/other_user}",
                        "gists_url"=> "https://api.github.com/users/devdrops/gists{/gist_id}",
                        "starred_url"=> "https://api.github.com/users/devdrops/starred{/owner}{/repo}",
                        "subscriptions_url"=> "https://api.github.com/users/devdrops/subscriptions",
                        "organizations_url"=> "https://api.github.com/users/devdrops/orgs",
                        "repos_url"=> "https://api.github.com/users/devdrops/repos",
                        "events_url"=> "https://api.github.com/users/devdrops/events{/privacy}",
                        "received_events_url"=> "https://api.github.com/users/devdrops/received_events",
                        "type"=> "User",
                        "site_admin"=> false
                    ),
                    "prerelease"=> false,
                    "created_at"=> "2017-06-01T14:11:46Z",
                    "published_at"=> "2017-06-01T15:38:47Z",
                    "assets"=> [],
                    "tarball_url"=> "https://api.github.com/repos/pagarme/pagarme-magento/tarball/v2.0.0",
                    "zipball_url"=> "https://api.github.com/repos/pagarme/pagarme-magento/zipball/v2.0.0",
                    "body"=> "Nova versão do módulo para Magento 1.x (> 1.7)"
                )
            )
        );
        $this->assertEquals('v0.1.6', $latestVersion['name']);
    }

}
