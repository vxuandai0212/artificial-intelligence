import requests

r2 = requests.post('http://hg0088.com/app/member/new_login.php', data= {
    'username': 'vnteam1234',
    'passwords': 'Abc121212',
    'langx': 'en-us',
    'auto': 'ICEICD',
    'blackbox': '',
    'nowsite': 'new'
})

tokens = r2.text.split('|')

r3 = requests.post("http://w986.hga025.com/", data = {
    'uid': tokens[2],
    'langx': tokens[4],
    'mtype': tokens[3],
    'today_gmt': '2019-02-20',
    'chghasLogin': 'login'
}, headers={
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    "Accept-Encoding": "gzip, deflate, br",
    "Accept-Language": "en-US,en;q=0.8",
    "Content-Type": "application/x-www-form-urlencoded",
    "Referer": "http://w986.hga025.com/app/member/index.php",
    "User-Agent": "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36"
})

print(r3.text)

login_url = "http://w986.hga025.com/app/member/FT_index.php?uid="+tokens[2]+"&langx="+tokens[4]+"&mtype="+tokens[3]+"&news_mem=Y"

print(login_url)

r4 = requests.get(login_url)

print(r4.text)
