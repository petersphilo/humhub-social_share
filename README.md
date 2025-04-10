# Social Share
This module allows people to post share links and be counted


<br><br>

## Donationware -- Consider a Donation!!

Your doantion would really, really, really, really help!  
https://www.paypal.com/donate/?hosted_button_id=AEA7Q4V5RMY4S

Thank You!

<br><br>

## ScreenShots

This is pretty much how it looks:

![ScreenShot 1](/assets/screen-1.jpg?raw=true "ScreenShot 1")  
![ScreenShot 2](/assets/screen-2.jpg?raw=true "ScreenShot 2")

<br><br>

## Other ways to install

### Installation (Using Git Clone)

- Clone the social_share module into your modules directory
```
cd protected/modules
git clone https://github.com/petersphilo/humhub-social_share.git social_share
```

- Go to Admin > Modules. You should now see the `Social Share` module in your list of installed modules

- Click "Enable". This will install the module for you

- Note: with HumHub 1.16.x, you need to add the following code to the file `protected/config/web.php`:
```
	'modules' => [
		'web' => [
			'security' =>  [
				'csp' => [
					'nonce' => false
				]
			]
		]
	]
```

Eventually, i hope to have this module in the 'store'

### Installation (Manually, using Release zip - for those not comfortable with the command line)

- Download the zip file from [/releases/latest](https://github.com/petersphilo/humhub-social_share/releases/latest)

- Upload it to the `protected/modules` directory of your HumHub installation and expand it (then delete the zip file)

- Go to Admin > Modules. You should now see the `Social Share` module in your list of installed modules

- Click "Enable". This will install the module for you

Eventually, i hope to have this module in the 'store'