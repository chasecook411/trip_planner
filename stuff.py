import requests

# url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=Vancouver+BC|Seattle&destinations=San+Francisco|Victoria+BC&mode=bicycling&language=en-EN&key=AIzaSyDudH82XEdtorLPxfFh8MyX_616Ns_QX24"

url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=place_id:ChIJ1QZkXjOHf4gRKEWuxnvX85g&destinations=place_id:ChIJZTNfS5-Af4gRg3GydqrK7No&key=AIzaSyDudH82XEdtorLPxfFh8MyX_616Ns_QX24"

r = requests.get(url)

print(r.json())