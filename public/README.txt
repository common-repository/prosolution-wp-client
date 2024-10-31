=== ProSolution WP Client ===
Contributors: Thomas, ProSolution
Donate link: https://prosolution.com`
Tags: profession, occupation, application, education, experience, expertise, attachment
Requires at least: 3.5
Tested up to: 4.9.5
Requires PHP: 5.6
Stable tag: 1.8.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Find job and apply , wordpress plugin for prosolution.com jobs (ProSolution WP Client)

== Description ==

A client for the job portal and online application feature of WorkExpert, the leading webbased ERP software for temporary work busines, produced and maintained by ProSolution (prosolution.com). The plugin only works with WorkExpert software as backend.


Plain Features list

* Search and Filter Job
* Apply for the job
* Api config and sync data in plugin backend
* Easy setup and automatic shortcode installation

One single shortcode handles everything.

[prosolfrontend ] Shows job searching application view, job list and apply


== Installation ==
How to install the plugin and get it working.

1. Install the plugin following any of the method from [here](https://codex.wordpress.org/Managing_Plugins)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activate there will be a new menu in left side menu called "ProSolution"
4. From the menu Pro Solution there is option for setting which needs to take care first, go to Api Config Tab
5. Put Api Domain url, Api User and Api Password and save, now you are all setup.
6. Check the second setting tab "Frontend" . This plugin automatically created page and inserted necessary shortcode for you, you can go to edit that page or visit, to see how it's work.
7. Frontend page in which the shortcode is added will show job searching portal and apply to job
8. More will come soon as per user demand and natural user experience
9. Go to dashboard "ProSolution" menu and click sync button for all necessary data tables, you can click sync all which will take time.
10. Prosolution -> Setting tab "Tools" has option to reset the tables and option values created by this plugin and cron job url with key for automatic sync.

== Frequently Asked Questions ==


== Screenshots ==
1. Frontend job search window
2. Frontend job search result
3. Frontend single job details
4. Frontend job apply form - Personal Data Step1
5. Frontend job apply form - Personal Data Step1(2)
6. Frontend job apply form - Education Step2
7. Frontend job apply form - Experience Data Step3
8. Frontend job apply form - Expertise Data Step4
9. Frontend job apply form - Expertise Data Step4(2)
10. Frontend job apply form - Side Dishes Data Step5
11. Frontend job apply form - Side Dishes Data Step5(2)
12. Backend  - All table list with sync status
13. Backend - Example country list table with country listing
14. Backend Setting - Api Config
15. Backend Setting - Frontend Setting
16. Backend Setting - Tools


== Changelog ==

= 1.8.7 =
* Add new mechanic, now jobdetail that have new or pending status can be accessed via workexpert recruitment module

= 1.8.6 =
* Add agentname detail only for recruitment, in jobsearch and jobdetail
* Add jobprojectid detail only for recruitment, in jobsearch and jobdetails
* Add customer detail only for recruitment, in jobsearch and jobdetail
* When option ProSouliton template selected, it will show more sub-settings:
  - FrontendDetails, Fields agentname: show / hide of agentname header at page type="details"
  - FrontendDetails, Fields jobprojectid: show / hide of jobprojectid header at page type="details"
  - FrontendDetails, Fields customer: show / hide of customer header at page type="details"
  - FrontendDetails, add input custom text in Fields customer
= 1.8.5 =
* remove warning message related to php 8
* hide menu expertise when pre-skill is not included
* add feature URL param 'source'

= 1.8.4 =
* Fix typo

= 1.8.3 =
* Fix coding related to php 8 (array_key_exists)
* Lower varchar to 55 of column jobid, siteid when create new table jobs

= 1.8.2 =
* Fix order items of jobs at page of job search based on latest publish date

= 1.8.1 =
* Fix synchronize table 'Jobs' to remove the limit how many jobs will return
* Fix table 'Jobs' is not created when activate plugin

= 1.8.0 =
* Add table jobstamp, use to record last saved jobuntil date
* Add table Jobs with custom buttons
  - Button "SynchAll", get all jobs and saved into table jobs
  - Button "SynchChanges", get jobs where modifydate is jobuntil (get value from table jobstamp)
  - Button "SynchChanges" default is disabled and will be enabled after user click button "SynchAll"
* Implement get searching jobs from database table Jobs (not anymore call API when user click button 'jobSearch')   
* Implement get job details from database table Jobs (not anymore call API when user click button 'jobDetail')    
* Implement cron job with interval every two hours. Activity of this job same as when user click button "SynchChanges"
* Implement SEO standard by shorten URL parameter at job search
* Repositioning anchor in all of title page
* Zipcode can be used as search term at job search
* Add logo at every page and can be upload in admin setting (tab design template)
* Autoformat of field "Date of Birth" at application form
* Add pagination (show 10 items per page) at job search
* set Recruitment as default value when install plugin for the first time
* set empty client list as default value when install plugin for the first time
* Fixed display worktime spacing to be in-line at job search result
* Fixed display title jobs and icon gps to be in-line at job search
* Fixed display gender "Divers" to be in-line vertically

= 1.7.7 =
* Add anchor in all of title page
* upgrade plugin design to responvise
* When application form steps setting set to "one pager", move privacy policy to bottom at application form
* Add shortcode [prosolfrontend type="result"], page will show all joblist at job search
* New feature "activate further skills", show / hide additional skill at application form
* Add new setting "activate further skills" at admin site tab application form

= 1.7.6 =
* Fixed custom field option when there's only have one option
* Fixed show client field setting when disable "enable recruitment"

= 1.7.5 =
* Fixed default office setting's selected option set to actual value
* Fixed privacy policy setting policy 1 and ppvc_date always set to mandatory
* Fixed privacy policy setting's activate based on selected site in application form

= 1.7.4 =
* Fixed pers_privacy_confirmdate year when submit form

= 1.7.3 =
* Fixed master site, in admin site tab general setting, default nation and office have option list
* Fixed submit form's destination as same as selected site
* Fixed button display of "next" at application form, when "Prosolution template" selected

= 1.7.2 =
* New feature "One pager", allow user to view shown steps in one page
* Add new fields setting "One pager" at admin site tab application form
* Fixed checkbox position when "no template" selected
* Fixed delete old cookie after activating new version

= 1.7.1 =
* Fixed frontend process when added url parameter siteid 
* Fixed additonal site, in admin site tab application form 1st Step when there is no Show following fields 
* Fixed pre-selected "Profession" at Application Form of Additional Sites

= 1.7.0 =
*  New feature "Additional Site", allow user to save multiple API destination with each own settings
* Add new tab in admin site called 'Additional Site'
  - user can add name of the site and id will be used in frontend
  - user can add / remove additional site
* In backend, add dropdown option to choose site will be used
* In frontend, add parameter url 'siteid=' to choose site will be used

= 1.6.4 =
* Fixed radio button position in application form when "no template" (design template setting) is chosen

= 1.6.3 =
* Fixed checkbox position in application form when "no template" (design template setting) is chosen

= 1.6.2 =
* Fixed page of jobsearch list when "no template" (design template setting) is chosen

= 1.6.1 =
* Fixed add new table to store data worktime

= 1.6.0 =
* New feature only for recruitment, "design template" 
* Add new tab in admin site called 'design template', with sub-settings 'select template'
  - tab will be hidden when recruitment is not enabled
* In sub-settings 'select template', choose between no template or ProSolution template:
  - no template use default template from wordpress
  - ProSolution template enable user to have custom template)
* When option ProSouliton template selected, it will show more sub-settings:
  - font: change all font belongs to plugin
  - Main color: change all colors of button, bullets (radio or checkbox type) and input box when focused
  - FrontendSearch, heading: change label heading in page type="search"
  - FrontendSearch, job title: change label placeholder of input box keyword job in page type="search"
  - FrontendSearch, place: change label placeholder of input box keyword place in page type="search"
  - FrontendSearch, search button: change label search button (show list of job) in page type="search"
  - FrontendSearch, jobid=0 button: change label jobid button (direct link to apply form with no jobid selected) in page type="search"
    (there is checkbox to show / hide jobid=0 button)
  - FrontendResult, Fields zipcode: show / hide of zipcode in list of job at page type="search"
  - FrontendResult, Fields place of work: show / hide of place of work in list of job at page type="search"
  - FrontendResult, Fields work time: show / hide of work time in list of job at page type="search"
  - FrontendResult, to job button: change label to job button (direct link to job details) in page type="search"
    (there is checkbox to show / hide jobid=0 button)
  - FrontendDetails, Fields zipcode: show / hide of zipcode header at page type="details"
  - FrontendDetails, Fields place of work: show / hide of place of work header at page type="details"
  - FrontendDetails, Fields work time: show / hide of work time header at page type="details"
  - FrontendDetails, Fields salary: show / hide of salary header at page type="details"
  - FrontendDetails, Fields profession: show / hide of profession header at page type="details"
  - FrontendDetails, Fields qualification: show / hide of qualification header at page type="details"
  - FrontendDetails, back button: change label to back button (direct link to list of job search) in page type="details"
  - FrontendDetails, apply button: change label to job button (direct link to apply form with jobid selected) in page type="details"
  - ApplyForm, button to details: change label button to details (direct link to selected job details) in page type="apply"
  - ApplyForm, button to search: change label button to search (direct link to job search) in page type="apply"
  - ApplyForm, button to home: change label button to home (direct link to homepage) in page type="apply"
  - ApplyForm, button next step: change label button next step in page type="apply"
  - ApplyForm, button back: change label to button back step in page type="apply"

= 1.5.21 =
* Increase timeout of send application form with upload files

= 1.5.20 =
* Fixed email's max character to 200 when sending application

= 1.5.19 =
* Change email's max character input from 50 to 200

= 1.5.18 =
* New setting to sort by Skillgroup at adminsite / application form, step Expertise

= 1.5.17 =
* Fixed order skill group by new column "skillgroup_orderno"

= 1.5.16 =
* New feature only for recruitment:
  - In application Form with selected job, add default and list of job which doesn't exists in table sync
  - In application Form with selected job, add list of skill which doesn't exists in table sync

= 1.5.15 =
* Fixed profession name on job search result when feature recruitment disabled

= 1.5.14 =
* Change mandatory setting first and sixth privacy policy to be editable

= 1.5.13 =
* Fixed button 'no matching job found' to dynamic url

= 1.5.12 =
* Fixed optional privacy policy set to not mandatory

= 1.5.11 =
* Fixed button 'search' url value

= 1.5.10 =
* Add new gender option 'diverse'

= 1.5.9 =
* New feature only for recruitment:
    - In adminsite/Setting/General Settings, add setting client list (filter for 'job search')
    - When feature recruitment disabled, hide setting client list
* Add salary detail only for recruitment, in jobdetail
* Fix customfield's label at job detail

= 1.5.8 =
* Fixed translation of step name's default value
* Reposition url check's button before submit

= 1.5.7 =
* Fixed all german tranlsation

= 1.5.6 =
* Fixed radio button spacing
* Remove button 'back to Job Search' when Application form set as main page

= 1.5.5 =
* New feature recruitment:
    - Added setting to enable feature recruitment in adminsite/Setting/General Settings
    - Added message in setting default office as required fields
    - New recruitment fields based on Employee Application WorkExpert and placed in Application Form 1st Step
    - Added new recruitment fields setting to enable show/hide and mandatory in adminsite/Setting/Application Form (profileText, profileOption, empgroup_ID, tagid)
    - Added new recruitment fields setting to edit text in adminsite/Setting/Privacy Policy (pers_privacy_confirmdate)
    - When feature recruitment enabled, all API changed from 'application' to 'recruitment'
    - Adjustment data view for recruitment only in page joblist and jobdetails
* Fixed translation german Postcode warning message in 1st Step
* Fixed several missing translation for german and spanish

= 1.5.4 =
* Fixed send button into disabled when only have 1st Step and single privacy policy
* New feature default office in adminsite/Setting/General Settings (default value is empty)
* Fixed translation german Postcode warning message in 1st Step
* Fixed translation german button 'Enter another Application'

= 1.5.3 =
* Fixed translation german 'Federal State' in 1st Step
* Fixed translation german 'Female' in 1st Step

= 1.5.2 =
* Fixed translation 'Postcode/Town' in 1st Step
* Fixed following fields doesn't received at Prosolution:
  - Title
  - Phone 1
  - Phone 2
  - Federal State
  - Nationality
  - Birth Country
  - Marital status
  - Available from
  - Social security Country

= 1.5.1 =
* Fixed validation behavior in step education and work experience
* Fixed Navigation tabs when 6th Step Label is not active
* Fixed remove button 'next' when activate 1st Step only
* Fixed remove double * when set to mandatory
* Fixed missing * when field 'phone' set to mandatory
* Remove feature show/hide and will always set to mandatory in several fields:
  - Education : group, training, beginning, end, federal, foact (field of activity), business
  - Experience : beginning, end, description
  As in API, these fields at step Education and Experience must exists by default (not ignoring all empty condition)

= 1.5.0 =
* new feature in adminsite/Setting/application form, add editable Step (change label, show / hide) and textarea (mandatory, show / hide)
* new feature in adminsite/Setting/privacy policy, add editable policy max to 5 sentence (mandatory, font can be styled)
* new feature in adminsite/Setting/API config, add button for URL validity
* menu name in adminsite/Setting/prosolution changed to 'Data Sync'
* menu name in adminsite/Setting/prosolution/Frontend changed to 'General Settings'
* languages update for new feature

= 1.4.3 =
* bugfix for file transfer encodings

= 1.4.2 =
* languages updates

= 1.4.1 =
* bug fixing in api configuration

= 1.4.0 =
* new language selection feature on adminsite
* new crawler page feature
* languages updates
* fixed available date bug on application form
* fixed performance bug when added multiple entries
* added condition of description must be filled on education form

= 1.3.2 =
* fixed uploading multiple documents/files

= 1.3.1 =
* several bugfixes and language updates

= 1.3.0 =
* fixed the error message in database synchronization admin (setting, country, office, etc)
* fixed the behaviour of selected rate skill (hardcode script) after selecting the group skill
* fixed the header of list skill problem when the content is scrolling after selecting the group skill

= 1.2.0 =
* first release

== Upgrade Notice ==

= 0.10 =
This is the first, fully featured version.