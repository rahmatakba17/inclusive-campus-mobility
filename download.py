import urllib.request
url = "https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Logo_UNHAS.svg/512px-Logo_UNHAS.svg.png"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
with urllib.request.urlopen(req) as response:
    with open("public/images/logo_unhas.png", "wb") as f:
        f.write(response.read())
