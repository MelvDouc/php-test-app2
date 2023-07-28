docker stop $1
docker rm $1
docker build --no-cache -t myphptestapp2 .
docker run -d -p 10000:80 --name $1 phptestapp2