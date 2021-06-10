import argparse
import datetime
import os
from pathlib import Path
import re
import time


if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("--dir", type=str, default="data")
    parser.add_argument("--txt", type=str, default="_chat.txt")
    args = parser.parse_args()

    # go over all chats
    root = Path(args.dir)
    for child in root.iterdir():

        # get text
        text = child / args.txt
        with open(text, "r") as f:
            lines = f.readlines()

        duplicates = {}
        patt = r"\[(.*)/(.*)/(.*), (.*):(.*):(.*)\] .*<attached: (.*)>"
        for line in lines:

            # match regex
            match = re.findall(patt, line)
            if match:

                # get info
                day, month, year, hour, minute, second, fname = match[0]
                # date = f"{year}:{month}:{day} {hour}:{minute}:{second}"
                new_fname = f"{year}{month}{day}_{hour}{minute}{second}"
                ext = fname.split(".")[1]
                new_fname_full = f"{new_fname}.{ext}"

                # check if image
                # try:
                #     Image.open(root / fname)
                #     image = True
                # except IOError:
                #     image = False

                # if image:
                    # get current exif
                    # exif = piexif.load(fname)
                    # remove old exif
                    # piexif.remove(fname)

                    # put in new exif
                # check if exists
                if not (child / fname).is_file():
                    continue
                
                # change modification and access time
                date = datetime.datetime(int(year), int(month), int(day), int(hour), int(minute), int(second))
                date = time.mktime(date.timetuple())
                os.utime(child / fname, (date, date))

                # change filename
                # check if new file already exists (some have same timestamp)
                if (child / new_fname_full).is_file():
                    if new_fname in duplicates:
                        # this is the 2nd/3rd/etc duplicate
                        duplicates[new_fname] += 1
                    else:
                        # this is the first duplicate
                        duplicates[new_fname] = 1
                    os.rename(child / fname, child / f"{new_fname}_{duplicates[new_fname]}.{ext}")
                else:
                    os.rename(child / fname, child / f"{new_fname_full}")
    