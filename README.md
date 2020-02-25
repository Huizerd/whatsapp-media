# whatsapp-media

Extract and organize Whatsapp media from iPhone. Original [source](https://www.youtube.com/watch?v=Q5J26FRySbQ&list=WL&index=6&t=0s) with updated code and procedures.

## Software (tested)

- Windows 10
- [iExplorer](https://macroplant.com/iexplorer) 4.3.4
- [DB Browser for SQLite](https://sqlitebrowser.org/dl/) 3.11.2 (64 bit)
- [XAMPP](https://www.apachefriends.org/download.html) 7.4.2 (64 bit)

## Steps

1. Install iExplorer and connect your iPhone
2. Open iExplorer and make a backup of your iPhone (it should prompt you to do this if it can't find an iTunes backup)
3. Find the Whatsapp media folder under `Backup Explorer\App Group\group.net.whatsapp.Whatsapp.shared\Message` and export it to your desktop (as `Media`)
4. Find `ChatStorage.sqlite` in `Backup Explorer\App Group\group.net.whatsapp.Whatsapp.shared`, export it to your desktop as well (original name)
5. Install DB Browser for SQLite, open `ChatStorage.SQLite`
6. Export the following tables to `.csv` (with `,` as field separator, `"` as quote character, `Windows: CR+` as new line character):
    - `ZWACHATSESSION` as `chats.csv` to desktop
    - `ZWAMEDIAITEM` as `media.csv` to desktop
    - `ZWAMESSAGE` as `messages.csv` to desktop
7. Install XAMPP in a location that is relatively easy to find (I just did `C:\xampp`)
8. Move the following items to `C:\xampp\htdocs\`:
    - `Media` folder
    - `chats.csv`, `media.csv`, `messages.csv`
    - `import.php`, `rename.php`, `flatten.php`, `thumbs.php`, `whatsapp.html` (this repository)
9. Make sure the `Media` folder (and subfolders) is not read-only
10. In `C:\xampp\php\php.ini`, replace `max_execution_time=120` with `max_execution_time=0` and `max_input_time=60` with `max_input_time=-1` to prevent time out errors
11. Open a browser and go to `localhost/whatsapp.html`
12. Use the scripts:
    - Import the CSV files
    - Rename all media (this can take some time)
    - Flatten the folder structure
    - Remove useless thumb files
13. Done!
