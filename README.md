# PrayerTimeReminder

PrayerTimeReminder is a PHP application designed for Islamic prayer time alerts. It dynamically fetches prayer times using the e-solat.gov.my API, offers voice reminders for each prayer, and sends email notifications in case of errors. This application helps users maintain their prayer schedule with timely reminders.

## Features

- **Dynamic Prayer Time Updates:** Utilizes the e-solat.gov.my API for the latest prayer times.
- **Voice Reminders:** Provides Adhan (call to prayer) audio playback at designated prayer times.
- **Email Error Notifications:** Configured to send email alerts for app-related errors.
- **Responsive Design:** Compatible across various devices for a seamless user experience.

## Getting Started

Follow these instructions to get the PrayerTimeReminder app up and running on your local machine for development and testing.

### Prerequisites

- PHP 7.4+
- MySQL Database
- Composer
- WAMP, XAMPP, or another local server environment

### Cloning the Repository

Clone the PrayerTimeReminder repository to your local machine:

```bash
git clone https://github.com/rahmanwahyudi/PrayerTimeReminder
cd PrayerTimeReminder
```

Replace `https://github.com/yourusername/PrayerTimeReminder.git` with your repository's URL.

### Installing Dependencies

Navigate to the project's root directory and install PHP dependencies with Composer:

```bash
composer install
```

### Configuration

1. **Environment File:** Rename the `.env-example` file to `.env` and update the values based on your local environment:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=prayer_time_reminder
BASE_PATH=/PrayerTimeReminder
SMTP_HOST=smtp.example.com
SMTP_USER=user@example.com
SMTP_PASS=secret
SMTP_PORT=587
SMTP_SECURE=tls
MAIL_FROM=from@example.com
MAIL_FROM_NAME=Mailer
```

2. **Database Setup:** Import the `prayer_time_reminder.sql` file into your MySQL database to set up the required database schema.


## Setting Up a Cron Job for Daily Updates

To ensure your prayer time data is always up-to-date, you can set up a cron job on your server that hits the `update-prayer-time` endpoint at the end of each day. Here's how to set it up:

1. Log into your server via SSH.

2. Open your crontab file for editing by running:
   ```bash
   crontab -e
   ```
   
3. Add the following line to schedule a daily request to the `update-prayer-time` endpoint. This example sets the cron job to run at 11:55 PM server time every day:
   ```
   55 23 * * * curl http://localhost/PrayerTimeReminder/update-prayer-time
   ```
   This uses `curl` to make a simple GET request to the endpoint. Ensure that the URL is correct and accessible from your server. Adjust the time if you prefer a different schedule.

4. Save and exit the editor. The cron job is now set up and will run at the specified time daily.

### Important Notes:

- **Server Time:** Cron jobs are scheduled based on the server's time zone. Ensure your server's time zone matches the intended schedule for updating prayer times.
  
- **Testing:** After setting up the cron job, monitor your application and server logs to ensure that the updates are running as expected without issues.

- **Security:** If your `update-prayer-time` endpoint makes changes to your database or has other side effects, consider securing this endpoint to prevent unauthorized access.

Adding this cron job ensures your PrayerTimeReminder app maintains current and accurate prayer times by fetching new data each day.


### Running the Application

Use WAMP, XAMPP, or a similar service to start your local server. Then, access the application by navigating to `http://localhost/PrayerTimeReminder/` in your web browser.

## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

## License

This project is licensed under the MIT License - see the LICENSE.md file for details.

## Acknowledgments

- Special thanks to the [e-solat.gov.my API](http://www.e-solat.gov.my/) for providing accurate prayer times.
