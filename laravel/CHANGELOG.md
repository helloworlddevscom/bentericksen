# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [1.20.0](http://helloworlddevs///compare/v1.19.0...v1.20.0) (2022-07-13)


### Features

* **business-export:** add business type selects to business export form ([6df877a](http://helloworlddevs///commit/6df877afc8a0c47eabce70c3f8821dd54f069e29))
* **employment:** remove check for can_rehire for rehire button ([cb1b64e](http://helloworlddevs///commit/cb1b64e17fa076575fb624de7c67e09d807e4679))
* **export-lists:** add filter for business type to business and email export lists ([f52f515](http://helloworlddevs///commit/f52f515cadcf97a9caf33aac5864cb9b15b5ae96))
* **expressions:** add expressions evaluator class ([e87ae5a](http://helloworlddevs///commit/e87ae5a727ec5d0aa23339e1853004be7a6860b9))
* **forms:** add business type selection to forms edit and create ([9105f3e](http://helloworlddevs///commit/9105f3e386b9d120612fb33e6a36da96910fe4ed))
* **forms:** add form template rules ([aff5425](http://helloworlddevs///commit/aff542533b79aa1dce04640e37570459d0e76990))
* **forms:** add industries attribute mutators ([e8a85bd](http://helloworlddevs///commit/e8a85bdeff3400c9d777ee636e7fe27fb895602b))
* **forms:** add industries selection field to form templates ([c7bb0df](http://helloworlddevs///commit/c7bb0df500a56e031f7771ce9c45d69eef12b8c2))
* **forms:** add migration for base form tempate rules ([a034e79](http://helloworlddevs///commit/a034e79eb4c494ad31904b91b99fe028eb289be6))
* **forms:** add min / max employee fields to form templates ([549489d](http://helloworlddevs///commit/549489d92df27fa6941c47eada5c6c3093164e28))
* **forms-controller:** update FormsController ([a0541ec](http://helloworlddevs///commit/a0541ec60c47ce445b04913acc899aed276b0513))
* **forms-populator:** add class for populating forms based on form template rules ([cfe408f](http://helloworlddevs///commit/cfe408f437c71e052b58a96a9f3a14d6b89aa226))
* **forms-populator:** add ids function for grabbing all template ids for a business ([bc0a142](http://helloworlddevs///commit/bc0a142e006882d6fd5b8eaddf7d08bdb5d836ae))
* **industries:** add new method for only getting main industries ([675f8fa](http://helloworlddevs///commit/675f8fa6787dec092bd247acf8774e559a389cb0))


### Bug Fixes

* **business-export:** fix form from merge ([c53311d](http://helloworlddevs///commit/c53311d612c279fc47776d08bdd4e85b3118da47))
* **forms:** fix employee count type value repopulation on form edit ([932237b](http://helloworlddevs///commit/932237b064de607ab38fc19b06732cb3e9d16ef7))
* **policies-controller:** change check for mods to be empty ([d18bd66](http://helloworlddevs///commit/d18bd66c64924840965e9cd10a82f94014cc7284))
* **policy-editor:** patch how is_tracking and mod_counter are set ([8c014fb](http://helloworlddevs///commit/8c014fb4c12719de122309af3f7223889327be14))

## [1.19.0](http://helloworlddevs///compare/v1.18.0...v1.19.0) (2022-06-01)


### Features

* **exportqueries:** update export queries for email list ([b73f394](http://helloworlddevs///commit/b73f394ae12f2c48f515a07f14bf3d5414d6c266))

## [1.18.0](http://helloworlddevs///compare/v1.17.0...v1.18.0) (2022-06-01)


### Features

* **business-export:** add top 10 active owners and managers for each business ([47f9c82](http://helloworlddevs///commit/47f9c820229a36a380b8ac048601188f1bcb3c1a))
* **cleanuppolicyarchive:** add cleanupPolicyArchive job ([bcd8bbf](http://helloworlddevs///commit/bcd8bbf1fd3c7080757c186eb9cfb0b8c1739e32))
* **compare:** add compare tool ([10aa8b9](http://helloworlddevs///commit/10aa8b9f37078ca1f34efc3ba78aae8902cb0bb7))
* **manual-print-service:** add auto-backup functionality when genrating a manual ([5a79f50](http://helloworlddevs///commit/5a79f50b659b552962da54d4057d41d0bcb20c02))
* **manual-print-service:** add purge logic to backup process ([c59cdcb](http://helloworlddevs///commit/c59cdcbc53a63b3241f66c1f00a9681ffd5d1061))
* **policy-compare:** restrict compare to admins only ([e85d5ad](http://helloworlddevs///commit/e85d5adbe74857af9f2327c4d0c007a836f249aa))
* **policy-compare:** update compare tool ([b3ed4c6](http://helloworlddevs///commit/b3ed4c6f5c49daa908dea19404505cf833eb9d87))
* **policy-compare:** update policy compare ([e9ee5c2](http://helloworlddevs///commit/e9ee5c29146df99d7e5b310d211185ea6d748f15))
* **policy-compare:** update policy-compare tool and test ([68470e3](http://helloworlddevs///commit/68470e3d9005a1f58593c614112f0e37576553e8))
* **policy-editor:** allow admins to access policy editor while policies are pending ([ca1ad40](http://helloworlddevs///commit/ca1ad408f0de4684e7e487cc6285fee7af923956))
* **policy-export:** add bulk policy export tool ([1ddf3a0](http://helloworlddevs///commit/1ddf3a00628a9d26d8c7b9996501a73f84ad4634))
* **policy-updates:** add yellow banner, policy manual generation notice ([db606ff](http://helloworlddevs///commit/db606ff0c479ed00292119b0a38ed769059b6466))
* **policy-updates:** implement new notice modal for policy updates ([ea26ace](http://helloworlddevs///commit/ea26ace2816c3e2e1026d5aa988de9ba45f224ce))
* **scheduler:** add cleanupPolicyArchive to scheduler ([7c88a0f](http://helloworlddevs///commit/7c88a0f90f53da2737b047b76155d8683f3aae26))


### Bug Fixes

* **migration:** temp fix migrations ([01fb209](http://helloworlddevs///commit/01fb20962f49b512e7297dfa17b10e9ffe050e72))
* **navigation:** set check admin permissions to viewUser ([a9d054e](http://helloworlddevs///commit/a9d054e846dcc8f8847290d9d374bd93c6912b56))
* **navigation:** switch all of navigation to use impersonated ([89b0ddd](http://helloworlddevs///commit/89b0ddd08a6f42717eac83da651ef5d6dc5737b6))
* **policies:** update the way policies are destroyed ([40885be](http://helloworlddevs///commit/40885be5407d46c8a2d2a885f15422350035ba76))
* **policy-compare:** disable compare button for custom policies ([8dd9557](http://helloworlddevs///commit/8dd95573f19f392ddcb159c6cb5461a5d1f1005c))
* **policy-compare:** fix html diffing ([8c2c517](http://helloworlddevs///commit/8c2c517f2cb3bd6b0639174ce8fbdbdaa057ec8e))
* **policy-compare:** fix storage path ([229a785](http://helloworlddevs///commit/229a7857cfcef5fff6009b441a9003184751c1e9))
* **policy-compare:** remove compare button from edit screen ([213ca65](http://helloworlddevs///commit/213ca6542ced603a436c72ee9d00a84a04060451))
* **policy-compare:** set storage location ([6c3d685](http://helloworlddevs///commit/6c3d68503697ee30fe1147457805278217db4085))
* **policy-editor:** fix policy saving again ([fba08db](http://helloworlddevs///commit/fba08db6333e1c049c6dec29b9a70c6484a0b369))
* **policy-update:** add check for body when selecting parents to hide after generating manual ([e258798](http://helloworlddevs///commit/e25879861e990d843aed421cc04d3795a0e9e377))

## [1.17.0](http://helloworlddevs///compare/v1.16.0...v1.17.0) (2021-08-20)


### Features

* **bonus-pro:** add further support for splitting and combining hygienist with main staff ([0815ff1](http://helloworlddevs///commit/0815ff1e85e71a09af4ebd1c6a38dfc45a996efe))


### Bug Fixes

* **bonuspro:** fix salary and percentage calculation in plan setup step 4 ([8734575](http://helloworlddevs///commit/87345750e61a4c20410aa5d2fb16570cb87e7acf))
* **bonuspro:** reconfigure logic to work to spec ([ba6bbb0](http://helloworlddevs///commit/ba6bbb05c892ae7c34236c57efe15b007b382e2d))

## [1.16.0](http://helloworlddevs///compare/v1.15.1...v1.16.0) (2021-05-28)


### Features

* **policycompare:** update compare ui ([8db7384](http://helloworlddevs///commit/8db738472c118a1fabc6db0b8684aca79a3bd388))


### Bug Fixes

* **auto-save:** fix submit selectors ([f42460f](http://helloworlddevs///commit/f42460f38bd8bf4630330b6a354a798682e16b5d))
* **exportservice:** updarte relationship between business_asas and business table ([f0a4014](http://helloworlddevs///commit/f0a40140076e5660caf29b5efb90f4888113d293))
* **login:** reload page on session timeeout to prevent stale state when logging in ([b401bb3](http://helloworlddevs///commit/b401bb36f6224e7215c4584009d028067355242a))
* **sessiontimeout:** disable autosave until issues resolved ([3c3610d](http://helloworlddevs///commit/3c3610d9c546fe0dd6ed5811ddd82574172f78a0))

### [1.15.1](http://helloworlddevs///compare/v1.15.0...v1.15.1) (2021-04-12)

## [1.15.0](http://helloworlddevs///compare/v1.14.0...v1.15.0) (2021-04-12)


### Features

* **bannerviewcomposer:** add impersonated attribute ([f829663](http://helloworlddevs///commit/f829663d87682bc25753a856afa06f84915826fb))
* **patchbusinessimport:** add job and importer for patching business statuses ([5178ac7](http://helloworlddevs///commit/5178ac756d7860cd291065504165bc1ee8c6a984))
* **patchcancelledbusiness:** add job to kernel ([9ebb90c](http://helloworlddevs///commit/9ebb90c2dc10d79a63b8d60a53c2f90b38cf6212))
* **patchexpiredbusinesses:** add update to set business status to cancelled ([f880c59](http://helloworlddevs///commit/f880c590294ce2534c300328e34f4885702fd70a))
* **sessiontimeout:** add timeoutmodal to admin ([3f388cb](http://helloworlddevs///commit/3f388cbad6dc40c18a58e492061209c3520c09f3))
* **sessiontimeout:** split autosave from initial modal display ([cf7042b](http://helloworlddevs///commit/cf7042b0b169b6ca519460bb434f3e1aa9e1aeea))
* **timeout:** add timeout modal to consultants ([6e801c1](http://helloworlddevs///commit/6e801c1460f2b189318c90462489ceecec0a6cf0))


### Bug Fixes

* **businesspoliciesmanager:** limit policies collected to just policies within effective date range ([a696ad5](http://helloworlddevs///commit/a696ad5db40bed756f7aa311c2c40d5ddd2f3b25))
* **patchcancelledbusiness:** patch update ([57dbd82](http://helloworlddevs///commit/57dbd826508e53f79f6dc5f2270d4d724854e2f3))
* **patchcancelledbusinesses:** remove old code ([116742b](http://helloworlddevs///commit/116742bd56dd9b068c70fc5a1d1dcf5a5a7cec70))
* **patchexpiredbusinesses:** use public disk to avoid read/write issues ([adfee46](http://helloworlddevs///commit/adfee46acb561d15ddc84faed869236366e21739))

## [1.14.0](http://helloworlddevs///compare/v1.13.0...v1.14.0) (2021-03-02)


### Features

* **bonuspro:** add toggle for bonuspro enable fund option ([cd4759d](http://helloworlddevs///commit/cd4759d747c0729b994eb6ffc0895d4c3d7db16e))

## [1.13.0](http://helloworlddevs///compare/v1.12.0...v1.13.0) (2021-02-17)


### Features

* **autosave:** add autosave for policy and job description editing ([aee8fb6](http://helloworlddevs///commit/aee8fb6a1365ee421a3f0bf7f84d0fc34c2651ec))
* **policy-updater:** link disabling policies to the policy updater ([f6d237e](http://helloworlddevs///commit/f6d237e51c0d52d9f5d43247dd8ad1e9b3f86852))
* **policycompare:** update policy compare modal ([01a1ecf](http://helloworlddevs///commit/01a1ecf2ce156fb7bcfef00c1de2f3f47f0e0332))
* **sessiontimeout:** add redirect to root path a minute after session expires ([5274428](http://helloworlddevs///commit/5274428e1418f8dee29f78d74320128834aa005b))
* **sessiontimeout:** make session timeout warning modal ([b6fdbf2](http://helloworlddevs///commit/b6fdbf207ee69a64af370669454088d1f7ee7c06))


### Bug Fixes

* **adminstatusswitch:** patch account expiration logic ([0f210fa](http://helloworlddevs///commit/0f210fa44a5085da3211b5e1d9b395c4b0fd7ed7))
* **bonuspro:** fix login params ([2f4ec02](http://helloworlddevs///commit/2f4ec02abd70bf53e1dc4c00804cb37e89a98558))
* **businesspoliciesmanager:** add method to clear disabled policies when updating ([4816897](http://helloworlddevs///commit/481689714344e99481a9df461b13bbacfe81458e))
* **employee:** fix employee routes ([f504861](http://helloworlddevs///commit/f50486163fca6f7a1e99ddd98487461c6a2a1427))
* **employee:** lock employee view when hrdirector not enabled ([3cf2932](http://helloworlddevs///commit/3cf2932ab5b81af6697bdf676fe95240d98b1a80))
* **policyupdateemail:** move flag to resend email job ([a789148](http://helloworlddevs///commit/a7891484f7d6d75aef419aa1273f51214e8b08a1))
* **session-timeout-modal:** remove parent selector and directly target the submit buttons ([3014cff](http://helloworlddevs///commit/3014cff22c6ecb9817840222e46f5a181ad3b5f7))
* **sessiontimeout:** patch auto-save ([6a6b95b](http://helloworlddevs///commit/6a6b95b74c80608a73417fa6833bdfa9dd5293ab))
* **user:** cache-bust css assets in user view ([385547e](http://helloworlddevs///commit/385547eada14bd030f7fff50dcb05a0dbe3f0025))
* **UserPolicyUpdate:** group updates by policy to prevent duplicates ([94317e6](http://helloworlddevs///commit/94317e6d61ddd0551e98175e1831d0e075cd1f95))

## [1.12.0](http://helloworlddevs///compare/v1.11.6...v1.12.0) (2020-11-23)


### Features

* **policyupdateemail:** add configuration option to skip if disabled ([e67dd50](http://helloworlddevs///commit/e67dd5006e4c049f3cb0c51689247fcd4cff0fd1))


### Bug Fixes

* **policy-updater:** add missing config file ([172e427](http://helloworlddevs///commit/172e427ad36ff8efe596351b91a3a9f3f69c7bf2))
* **salaries:** make salary reason fields nullable ([e962ab8](http://helloworlddevs///commit/e962ab8357eab89a30cfc6abe42be03ad7cc3c35))
* **test-e2e-all:** fix exec permissions ([12ec99f](http://helloworlddevs///commit/12ec99f5813035481ba6084608015dbd5aa3b1d2))
* **time_off_request:** fix drilldown from view more modal ([3d768b4](http://helloworlddevs///commit/3d768b4754ff68a499dd79095ad83f5f806cba4b))
* **time_off_request:** update format to Date constructor ([f9d057e](http://helloworlddevs///commit/f9d057e8fb0c68728d9f34b5de09ca8f1ccbdd62))
* **viewmoremodal:** make date header more readable ([6f5526d](http://helloworlddevs///commit/6f5526d6a115975d9104788f3e7d3452b88dc9af))

### [1.11.2](http://helloworlddevs///compare/v1.11.1...v1.11.2) (2020-09-10)

### [1.11.1](http://helloworlddevs///compare/v1.11.0...v1.11.1) (2020-09-09)

## [1.11.0](http://helloworlddevs///compare/v1.10.6...v1.11.0) (2020-09-09)


### Features

* **policy-updates:** enable disabled accounts to receive policy updates ([0e7222e](http://helloworlddevs///commit/0e7222e64ae442e1305dc84b45a5dc1c38b476de))


### Bug Fixes

* **ChangeSalary:** fix busted code from update ([ba68df6](http://helloworlddevs///commit/ba68df6af111b9ac6b9a8803ad440e85605b497f))
* **config:** restore custom configs cleared by upgrade process ([7b59bc6](http://helloworlddevs///commit/7b59bc6ea3952b18a128616414d93aae97c1c6f3))
* **database:** make columns nullable that are optional ([822a49f](http://helloworlddevs///commit/822a49fcdf8a76fdd7fab67da44519b77b9c68ec))
* **database:** turn off strict mode to allow app to function without restructuring database ([04de49a](http://helloworlddevs///commit/04de49a42f5c668451f69335b7e31b01463f653c))
* **Exception\Handler:** make exception handler compatible to prevent breaking ([4373888](http://helloworlddevs///commit/43738881a2b6610aa25d4a4623f98a9745d3521d))
* **migration:** fix for manual created at on business table ([bcc60c4](http://helloworlddevs///commit/bcc60c464e05762645290a67d20d4c26ab6da512))
* **migrations:** update date fields to match definition on servers ([2bc9dd6](http://helloworlddevs///commit/2bc9dd600f4e70bd3a2de7137544950518b1846e))
* **policyupdateemail:** update email job so that secondary owners with no user account get notified ([3fe80b3](http://helloworlddevs///commit/3fe80b33a363e7e53b8fc9aed10f4304d75e57cb))
* **routes:** fix auth routes ([8fd2104](http://helloworlddevs///commit/8fd2104414c984a0a134fcafece5c19627543eba))
* **seededtestcase:** reference testcase in the same namespace ([f93b94f](http://helloworlddevs///commit/f93b94ffa21102b6d832e14a66c510b99da82ffe))
* **userpolicyupdates:** explicitly set accepted_at value ([2b68512](http://helloworlddevs///commit/2b6851286b0772f8cda95677478cd5f05a993fe7))
* **userpolicyupdates:** update migration to match production db definition ([75b71aa](http://helloworlddevs///commit/75b71aa7e2963a575d1fcf4bc4ce780a0082c8da))

<a name="1.10.6"></a>
## [1.10.6](http://helloworlddevs/bentericksen/compare/v1.10.5...v1.10.6) (2020-07-22)


### Bug Fixes

* **TimeOffRequestsRequest:** allow either after or equal for end date compared to start date ([218e5dc](http://helloworlddevs/bentericksen/commits/218e5dc))



<a name="1.10.5"></a>
## [1.10.5](http://helloworlddevs/bentericksen/compare/v1.10.4...v1.10.5) (2020-07-17)



<a name="1.10.4"></a>
## [1.10.4](http://helloworlddevs/bentericksen/compare/v1.10.3...v1.10.4) (2020-07-15)



<a name="1.10.3"></a>
## [1.10.3](http://helloworlddevs/bentericksen/compare/v1.10.2...v1.10.3) (2020-07-11)


### Bug Fixes

* **policyCompare:** add overflow property to modal container to allow scrolling ([adaf034](http://helloworlddevs/bentericksen/commits/adaf034))



<a name="1.10.2"></a>
## [1.10.2](http://helloworlddevs/bentericksen/compare/v1.10.1...v1.10.2) (2020-06-17)


### Bug Fixes

* **SetLastLogin:** only fire SetLastLogin even during login ([6b321e3](http://helloworlddevs/bentericksen/commits/6b321e3))



<a name="1.10.1"></a>
## [1.10.1](http://helloworlddevs/bentericksen/compare/v1.10.0...v1.10.1) (2020-06-09)


### Bug Fixes

* **TimeOffController:** set default value for startingDate ([001c985](http://helloworlddevs/bentericksen/commits/001c985))



<a name="1.10.0"></a>
# [1.10.0](http://helloworlddevs/bentericksen/compare/v1.9.4...v1.10.0) (2020-06-05)


### Bug Fixes

* **ckeditor:** add cachebust for user instance of ckeditor ([84a408a](http://helloworlddevs/bentericksen/commits/84a408a))
* **ckeditor:** update admin config file ([a08e348](http://helloworlddevs/bentericksen/commits/a08e348))
* **HTMLPolicyCleanup:** add exceptions for margins on paragraph tags ([c3d172f](http://helloworlddevs/bentericksen/commits/c3d172f))
* **PoliciesController:** default back to current setting when no modifications made ([32ea8da](http://helloworlddevs/bentericksen/commits/32ea8da))


### Features

* **restore:** add restore script ([91bf2b4](http://helloworlddevs/bentericksen/commits/91bf2b4))
* **supervisor:** add supervisor process for workers ([7ac8e4c](http://helloworlddevs/bentericksen/commits/7ac8e4c))



<a name="1.9.4"></a>
## [1.9.4](http://helloworlddevs/bentericksen/compare/v1.9.3...v1.9.4) (2020-05-21)


### Bug Fixes

* **EmployeesController:** fix check for licensure update ([3513d5d](http://helloworlddevs/bentericksen/commits/3513d5d))


### Performance Improvements

* **PolicyUpdater:** update policy updater email storage ([9764a66](http://helloworlddevs/bentericksen/commits/9764a66))



<a name="1.9.3"></a>
## [1.9.3](http://helloworlddevs/bentericksen/compare/v1.9.2...v1.9.3) (2020-05-14)



<a name="1.9.2"></a>
## [1.9.2](http://helloworlddevs/bentericksen/compare/v1.9.1...v1.9.2) (2020-05-08)


### Bug Fixes

* **PolicyUpdateEmail:** fix email to_address when sending additional emails ([64a71c9](http://helloworlddevs/bentericksen/commits/64a71c9))



<a name="1.9.1"></a>
## [1.9.1](http://helloworlddevs/bentericksen/compare/v1.9.0...v1.9.1) (2020-04-30)



<a name="1.9.0"></a>
# [1.9.0](http://helloworlddevs/bentericksen/compare/v1.8.0...v1.9.0) (2020-04-23)


### Bug Fixes

* **patchEnabledStubs:** update patch command to go through all stubs regardless of status and update ([405a77a](http://helloworlddevs/bentericksen/commits/405a77a))


### Features

* **queue-work:** add convenience script for running queue worker ([fa24c33](http://helloworlddevs/bentericksen/commits/fa24c33))
* **queue-work:** kill default timeout ([5b0170a](http://helloworlddevs/bentericksen/commits/5b0170a))
* **queues:** init queues setup. add db tables for queues and failed_queues ([435c785](http://helloworlddevs/bentericksen/commits/435c785))
* **RunPolicyUpdates:** add job to run policy updates in the background ([8113f16](http://helloworlddevs/bentericksen/commits/8113f16))



<a name="1.8.0"></a>
# [1.8.0](http://helloworlddevs/bentericksen/compare/v1.7.3...v1.8.0) (2020-04-17)


### Bug Fixes

* **PatchEnabledStubs:** remove scratch code ([e71cea1](http://helloworlddevs/bentericksen/commits/e71cea1))
* **PoliciesController:** add check for stubs when updating ([b32e1b2](http://helloworlddevs/bentericksen/commits/b32e1b2))


### Features

* **PatchEnabledStubs:** add cli command that will patch stubs that are enabled when they sholdn't b ([351a971](http://helloworlddevs/bentericksen/commits/351a971))
* **policies:** add is_custom flag to policies. add seed to populate policies that can be assumed cu ([cb66be4](http://helloworlddevs/bentericksen/commits/cb66be4))
* **policies:** implement delete feature for custom policies when viewing as an admin ([13cddef](http://helloworlddevs/bentericksen/commits/13cddef))
* **TestPolicyUpdateProcess:** add command to automate update process ([e1c2e3f](http://helloworlddevs/bentericksen/commits/e1c2e3f))



<a name="1.7.3"></a>
## [1.7.3](http://helloworlddevs/bentericksen/compare/v1.7.2...v1.7.3) (2020-04-17)



<a name="1.7.2"></a>
## [1.7.2](http://helloworlddevs/bentericksen/compare/v1.7.1...v1.7.2) (2020-04-10)


### Bug Fixes

* **build:** remove clean processes ([49cf8f5](http://helloworlddevs/bentericksen/commits/49cf8f5))
* **build:** remove vendor folder before checkout ([a6c551c](http://helloworlddevs/bentericksen/commits/a6c551c))



<a name="1.7.1"></a>
## [1.7.1](http://helloworlddevs/bentericksen/compare/v1.7.0...v1.7.1) (2020-03-27)


### Bug Fixes

* **ckeditor:** add and remove source button under the conditions specified in ticket ([a1dce93](http://helloworlddevs/bentericksen/commits/a1dce93))
* **ckeditor:** patch issue where object is trying ot be accessed before instantiated ([b527d00](http://helloworlddevs/bentericksen/commits/b527d00))



<a name="1.7.0"></a>
# [1.7.0](http://helloworlddevs/bentericksen/compare/v1.6.1...v1.7.0) (2020-03-12)


### Bug Fixes

* **AcceptLicense:** update logic to consider BonusPro settings ([1ba8f86](http://helloworlddevs/bentericksen/commits/1ba8f86))
* **BonusPro:** fix redirect loop issue ([a052777](http://helloworlddevs/bentericksen/commits/a052777))
* **Business:** fix business renewal logic ([93fe011](http://helloworlddevs/bentericksen/commits/93fe011))
* **BusinessPoliciesManager:** fix sorting when generating policies for a business ([e9d8331](http://helloworlddevs/bentericksen/commits/e9d8331))
* **HrDirector:** update routing logic ([ae12555](http://helloworlddevs/bentericksen/commits/ae12555))
* **policy-updater:** fix process for accepting updates for disabled policies ([58bd69f](http://helloworlddevs/bentericksen/commits/58bd69f))
* **UserPolicyUpdates:** fix force option getter ([e230d24](http://helloworlddevs/bentericksen/commits/e230d24))


### Features

* **EmployeesController:** dispatch event for owner or managers when employee created ([9cc1cec](http://helloworlddevs/bentericksen/commits/9cc1cec))
* **PolicyNoticications:** create and dispatch event when new employee added. ([dc64197](http://helloworlddevs/bentericksen/commits/dc64197))
* **User:** add getRoleNames function ([ddb0449](http://helloworlddevs/bentericksen/commits/ddb0449))


### Performance Improvements

* **UserPolicyUpdates:** improve job lookup performance ([6edfc2d](http://helloworlddevs/bentericksen/commits/6edfc2d))



<a name="1.6.1"></a>
## [1.6.1](http://helloworlddevs/bentericksen/compare/v1.6.0...v1.6.1) (2020-02-28)



<a name="1.6.0"></a>
# [1.6.0](http://helloworlddevs/bentericksen/compare/v1.5.2...v1.6.0) (2020-02-21)


### Bug Fixes

* **AddEmployees:** add additional filter for employees ([3d6169a](http://helloworlddevs/bentericksen/commits/3d6169a)), closes [/github.com/HelloWorldDevs/bentericksen/pull/201#pullrequestreview-361207717](http://helloworlddevs/bentericksen/issues/pullrequestreview-361207717)
* **AddEmployees:** add computed employee filter ([4e38ff9](http://helloworlddevs/bentericksen/commits/4e38ff9))
* **bonuspro:** fix bonuspro auth reset workflow issues ([700c6bd](http://helloworlddevs/bentericksen/commits/700c6bd))
* **BonusProPasswordController:** add missing variable ([b4d872d](http://helloworlddevs/bentericksen/commits/b4d872d))
* **BonusProPasswordController:** add missing variable ([a1df13b](http://helloworlddevs/bentericksen/commits/a1df13b))
* **createEmployee:** return monthlyData so create process doesn't trip on data it thinks should be t ([0abc9bd](http://helloworlddevs/bentericksen/commits/0abc9bd))
* **EmployeeData:** fix typo ([156334c](http://helloworlddevs/bentericksen/commits/156334c)), closes [/github.com/HelloWorldDevs/bentericksen/pull/197#pullrequestreview-360763312](http://helloworlddevs/bentericksen/issues/pullrequestreview-360763312)
* **navigation:** fix bonuspro navigation ([870d4f4](http://helloworlddevs/bentericksen/commits/870d4f4))
* **navigation:** remove business id from menu ([fbf5ea5](http://helloworlddevs/bentericksen/commits/fbf5ea5))
* **Plan:** default back to created by user if no reset user set ([a25e9d9](http://helloworlddevs/bentericksen/commits/a25e9d9))
* **PoliciesController:** update policy requirement on update ([a480c25](http://helloworlddevs/bentericksen/commits/a480c25))


### Features

* **bonuspro:** add ability to set up hygiene on an existing plan in the settings panel ([28d3381](http://helloworlddevs/bentericksen/commits/28d3381))
* **bonuspro:** update email reset logic ([2d30630](http://helloworlddevs/bentericksen/commits/2d30630))
* **bonuspro:** update email reset logic ([3767f36](http://helloworlddevs/bentericksen/commits/3767f36))
* **BonusPro:** add bonus pro employee eligible flag ([9692629](http://helloworlddevs/bentericksen/commits/9692629))
* **BonusPro:** update password reset ([81a13cb](http://helloworlddevs/bentericksen/commits/81a13cb))
* **BonusPro:** update password reset ([bbe95ee](http://helloworlddevs/bentericksen/commits/bbe95ee))
* **filters:** add month filter ([030ff86](http://helloworlddevs/bentericksen/commits/030ff86))
* **planData:** add months getter ([aff465b](http://helloworlddevs/bentericksen/commits/aff465b))



<a name="1.5.2"></a>
## [1.5.2](http://helloworlddevs/bentericksen/compare/v1.5.1...v1.5.2) (2020-02-14)


### Bug Fixes

* **bonuspro:** add field limiters on year and month fields ([5be78e2](http://helloworlddevs/bentericksen/commits/5be78e2))



<a name="1.5.1"></a>
## [1.5.1](http://helloworlddevs/bentericksen/compare/v1.5.0...v1.5.1) (2020-02-07)


### Bug Fixes

* **bonuspro:** save last step on step change ([b38763d](http://helloworlddevs/bentericksen/commits/b38763d))
* **MonthModal:** patch ability to save when notActive set ([b788fc3](http://helloworlddevs/bentericksen/commits/b788fc3))



<a name="1.5.0"></a>
# [1.5.0](http://helloworlddevs/bentericksen/compare/v1.4.0...v1.5.0) (2020-01-31)


### Bug Fixes

* **bonus-pro:** fix issues with plan data workflow ([13b9dec](http://helloworlddevs/bentericksen/commits/13b9dec))
* **bonus-pro:** fix saving issues ([1a711ab](http://helloworlddevs/bentericksen/commits/1a711ab))
* **bonuspro:** fix monthly data tab ([90752be](http://helloworlddevs/bentericksen/commits/90752be))
* **bonuspro:** set default current_step value ([27ef8cb](http://helloworlddevs/bentericksen/commits/27ef8cb))
* **BonusPro:** patch saving issue ([70d8cdf](http://helloworlddevs/bentericksen/commits/70d8cdf))
* **BonusPro:** set createdBy and businessId first before setting and saving data ([94af019](http://helloworlddevs/bentericksen/commits/94af019))
* **BonusPro:** set createdBy and businessId first before setting and saving data ([5914b12](http://helloworlddevs/bentericksen/commits/5914b12))
* **BonusProController:** fix logic for adding a new month once months re-ordered ([2c19801](http://helloworlddevs/bentericksen/commits/2c19801))
* **BonusProController:** fix workflow issues ([289fe57](http://helloworlddevs/bentericksen/commits/289fe57))
* **BonusProService:** short circuit on invalid actions ([de45fae](http://helloworlddevs/bentericksen/commits/de45fae))
* **config:** grab url for site from dot-env file ([2672746](http://helloworlddevs/bentericksen/commits/2672746))
* **EditForm:** add autosave when switching tabs ([595a932](http://helloworlddevs/bentericksen/commits/595a932))
* **EmployeeList:** fix bad node name ([0ba4021](http://helloworlddevs/bentericksen/commits/0ba4021))
* **EmployeeList:** fix bad node name ([bf92066](http://helloworlddevs/bentericksen/commits/bf92066))
* **initialSetup:** fix save workflow ([475c6ae](http://helloworlddevs/bentericksen/commits/475c6ae))
* **InitialSetup:** add required indicator to field in InitialSetup ui ([ac3fdb8](http://helloworlddevs/bentericksen/commits/ac3fdb8))
* **navigation:** fix disabled bonuspro link markup ([e82dcc2](http://helloworlddevs/bentericksen/commits/e82dcc2))
* **navigation:** uncomment out disabled link for bonus pro when criteria met ([a62ac81](http://helloworlddevs/bentericksen/commits/a62ac81))
* **OutgoingEmail:** fix test for app environment for sending test emails ([9d56c49](http://helloworlddevs/bentericksen/commits/9d56c49))
* **planData:** fix index lookup when calculating ([70cdae8](http://helloworlddevs/bentericksen/commits/70cdae8))
* **planData:** normalize call to save plan data endpoint ([5f7f99e](http://helloworlddevs/bentericksen/commits/5f7f99e))
* **planData:** remove index preventing active month from being calculated ([6855cd6](http://helloworlddevs/bentericksen/commits/6855cd6))
* **PlanData:** pre-select text on focus ([ba017c1](http://helloworlddevs/bentericksen/commits/ba017c1))
* **ui:** syncrhonously progress each step ([e96d23a](http://helloworlddevs/bentericksen/commits/e96d23a))


### Features

* **createForm:** add createForm component ([86b0159](http://helloworlddevs/bentericksen/commits/86b0159))
* **EmployeeWarning:** implement bootstrap 4 employee modal ([3d6bdf3](http://helloworlddevs/bentericksen/commits/3d6bdf3))
* **MonthlyData:** re-implement sort the simplest way possible ([c11a95f](http://helloworlddevs/bentericksen/commits/c11a95f))
* **MonthlyModal:** add intermediate step between data and preview tabs if any numeric fields left a ([c7ef06b](http://helloworlddevs/bentericksen/commits/c7ef06b))
* **MonthModal:** add montly totals to both screens ([212086f](http://helloworlddevs/bentericksen/commits/212086f))
* **MonthModal:** modify cancel behavior when on dataWarning tab ([1b14037](http://helloworlddevs/bentericksen/commits/1b14037))
* **OutputConfigValues:** add command to output config values ([142d5c7](http://helloworlddevs/bentericksen/commits/142d5c7))
* **Permissions:** add ACL support for managers with access to BonusPro ([17f636e](http://helloworlddevs/bentericksen/commits/17f636e))
* **Permissions:** grey out and disable links related to hrdirector when only bonus pro available ([cdde2c9](http://helloworlddevs/bentericksen/commits/cdde2c9))
* **Plan:** sort plan month data by year and month ([3952e84](http://helloworlddevs/bentericksen/commits/3952e84))
* **Plan:** sort plan month data by year and month ([6654fac](http://helloworlddevs/bentericksen/commits/6654fac))
* **PlanData:** add autofocus to attributes when ui triggered ([c258c3e](http://helloworlddevs/bentericksen/commits/c258c3e))
* **PlanData:** add autofocus to attributes when ui triggered ([356f76f](http://helloworlddevs/bentericksen/commits/356f76f))



<a name="1.4.0"></a>
# [1.4.0](http://helloworlddevs/bentericksen/compare/v1.3.1...v1.4.0) (2020-01-17)


### Bug Fixes

* **EmployeeList:** fix bad node name ([a47d2f6](http://helloworlddevs/bentericksen/commits/a47d2f6))
* **EmployeeList:** fix bad node name ([2eca1cb](http://helloworlddevs/bentericksen/commits/2eca1cb))
* **InitialSetup:** add required indicator to field in InitialSetup ui ([eeb5792](http://helloworlddevs/bentericksen/commits/eeb5792))


### Features

* **EmployeeWarning:** implement bootstrap 4 employee modal ([2a05543](http://helloworlddevs/bentericksen/commits/2a05543))
* **Permissions:** add ACL support for managers with access to BonusPro ([fa671ba](http://helloworlddevs/bentericksen/commits/fa671ba))



<a name="1.3.1"></a>
## [1.3.1](http://helloworlddevs/bentericksen/compare/v1.3.0...v1.3.1) (2020-01-10)



<a name="1.3.0"></a>
# [1.3.0](http://helloworlddevs/bentericksen/compare/v1.2.1...v1.3.0) (2020-01-09)


### Bug Fixes

* **Api:** fix queries for name searching ([83b602f](http://helloworlddevs/bentericksen/commits/83b602f))
* **bonuspro-auth:** send to resume if plan not complete on successful password reset ([ab61d27](http://helloworlddevs/bentericksen/commits/ab61d27))
* **Businesses:** Prevent terminated employees from being re-enabled ([f20291d](http://helloworlddevs/bentericksen/commits/f20291d))
* **BusinessList:** fix user model and class attribute for business list ([74c7521](http://helloworlddevs/bentericksen/commits/74c7521))
* **EmployeeData:** clean up unused first approach ([504bc74](http://helloworlddevs/bentericksen/commits/504bc74))
* **EmployeeData:** fix add employee workflow ([a5b8c0a](http://helloworlddevs/bentericksen/commits/a5b8c0a))
* **Frontend:** scope bootstrap to importing components ([eff4c83](http://helloworlddevs/bentericksen/commits/eff4c83))
* **Lists:** add cursor pointer to column headers ([0508aca](http://helloworlddevs/bentericksen/commits/0508aca))
* **LoginList:** fix misspelled attribute for view as functionality ([7b8a8cf](http://helloworlddevs/bentericksen/commits/7b8a8cf))
* **PolicyUpdateResendEmail:** update subject ([ea3a357](http://helloworlddevs/bentericksen/commits/ea3a357))


### Features

* **bonuspro-auth:** add bonuspro auth controller logic ([5929d95](http://helloworlddevs/bentericksen/commits/5929d95))
* **bonuspro-auth:** add bonuspro auth routes ([14715b9](http://helloworlddevs/bentericksen/commits/14715b9))
* **bonuspro-auth:** add bonuspro auth views ([d5523ef](http://helloworlddevs/bentericksen/commits/d5523ef))
* **bonuspro-auth:** add bonuspro guard, provider, and password broker ([cb4e088](http://helloworlddevs/bentericksen/commits/cb4e088))
* **bonuspro-auth:** add db migration for bonuspro auth ([d8165be](http://helloworlddevs/bentericksen/commits/d8165be))
* **bonuspro-auth:** add middlware to check bonuspro plans guard ([5cd5f45](http://helloworlddevs/bentericksen/commits/5cd5f45))
* **bonuspro-auth:** add support for logout ([4d8720e](http://helloworlddevs/bentericksen/commits/4d8720e))
* **bonuspro-auth:** streamline password reset process ([73324ff](http://helloworlddevs/bentericksen/commits/73324ff))
* **bonuspro-plans:** add contitional redirect on login ([e8c36a1](http://helloworlddevs/bentericksen/commits/e8c36a1))
* **bonuspro-plans:** update save flow to store the last set step ([72b084c](http://helloworlddevs/bentericksen/commits/72b084c))
* **BonusPro/Plan:** add auth and reset traits and properties ([bdabb18](http://helloworlddevs/bentericksen/commits/bdabb18))
* **BonusProPasswordReset:** add password reset notification for bonus pro plans ([17e0e4f](http://helloworlddevs/bentericksen/commits/17e0e4f))
* **BusinessController:** add paginated business list endpoint ([1726e6c](http://helloworlddevs/bentericksen/commits/1726e6c))
* **BusinessList:** introduce client side rendered business list ([5dffd7d](http://helloworlddevs/bentericksen/commits/5dffd7d))
* **BusinessList:** optimize query for business list and add sorting ([88f3f84](http://helloworlddevs/bentericksen/commits/88f3f84))
* **LoginList:** introduce client side rendered login list ([a41b1d3](http://helloworlddevs/bentericksen/commits/a41b1d3))
* **LoginList:** pass auth-id for ui logic to component ([0374d56](http://helloworlddevs/bentericksen/commits/0374d56))
* **PolicyUpdateResendEmail:** update PolicyUpdateResendEmail command ([c006142](http://helloworlddevs/bentericksen/commits/c006142))


### Performance Improvements

* **BusinessList:** cleanup old code from previous approach ([2ee7373](http://helloworlddevs/bentericksen/commits/2ee7373))


### Reverts

* **PolicyUpdateResendEmail:** undo removal of command that sends this email ([996b57f](http://helloworlddevs/bentericksen/commits/996b57f))



<a name="1.2.1"></a>
## [1.2.1](http://helloworlddevs/bentericksen/compare/v1.2.0...v1.2.1) (2020-01-09)


### Bug Fixes

* **time_off_request:** patch data transform for calendar ([df92595](http://helloworlddevs/bentericksen/commits/df92595))



<a name="1.2.0"></a>
# [1.2.0](http://helloworlddevs/bentericksen/compare/v1.1.1...v1.2.0) (2019-10-28)


### Bug Fixes

* **BusinessPoliciesManager:** set sort order when adding new policies ([429c3b6](http://helloworlddevs/bentericksen/commits/429c3b6))
* **PoliciesController:** fix logic for using either BusinessPolicyUpdate or UserPolicyUpdate ([33c0815](http://helloworlddevs/bentericksen/commits/33c0815))
* **policyCompare:** fix issue where policy compare logic was failing ([9b2837b](http://helloworlddevs/bentericksen/commits/9b2837b))
* **PolicyUpdater:** fix simulated policy template update process ([1b1cef7](http://helloworlddevs/bentericksen/commits/1b1cef7))


### Features

* **PolicyUpdaterController:** set defaults for policy updater controller for emails that go out reg ([e7afe23](http://helloworlddevs/bentericksen/commits/e7afe23))
* **SetDoNotContact:** add SetDoNotContact command ([c4d5468](http://helloworlddevs/bentericksen/commits/c4d5468))



<a name="1.1.1"></a>
## [1.1.1](http://metaltoad/bentericksen/compare/v1.1.0...v1.1.1) (2019-08-05)


### Bug Fixes

* **editor:** update policy editor to use different technique that doesn't auto save ([efdbb33](http://metaltoad/bentericksen/commits/efdbb33))



<a name="1.1.0"></a>
# 1.1.0 (2019-08-05)


### Bug Fixes

* **EmployeesController:** fix misallocated message and patch type issue when calling withSuccess ([08cb105](http://metaltoad/bentericksen/commits/08cb105))
* **PoliciesController:** remove unused injection parameter ([328d5c2](http://metaltoad/bentericksen/commits/328d5c2))
* **policy.edit:** replace all form submits with updated policy save call ([b0037c1](http://metaltoad/bentericksen/commits/b0037c1))
* **PolicyModificationEmail:** fix variable reference in template ([b572f3f](http://metaltoad/bentericksen/commits/b572f3f))
* **time_off_request:** patch faulty markup for approve/deny buttons in modal ([b52d33e](http://metaltoad/bentericksen/commits/b52d33e))


### Features

* **policies:** add ability to programmatically insert page-breaks per policy ([23d6a37](http://metaltoad/bentericksen/commits/23d6a37))
* **policies:** add ability to programmatically insert page-breaks per policy ([6cd320b](http://metaltoad/bentericksen/commits/6cd320b))
