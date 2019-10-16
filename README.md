# easy-image-classify

Easy Image Classification on your own data set using deep learning without a single line of code. Great for end users.

## Highlights
- No coding required
- No need to have a large data set. Just a few images of each class is fine. 
- Easy Operation
- No need to learn python, tensorflow, caffe2 etc. 
- No need to know about YOLO, darknet and other highly technical things.

## Setup Instructions
- Download or Clone the repo.
- Upload the contents of /www folder to a live domain. It is a must. It will not work from localhost.
- You can put in any path on your domain like
http://<www.yourdomain.com>/easy-image-classify/

and that's it.

Just open a browser and browse
http://<www.yourdomain.com>/easy-image-classify/

You will see an screen like this;

![Create Model](https://raw.githubusercontent.com/theprodev/easy-image-classify/master/screenshots/create-model.jpg)

Now you need to create a zip file of all your custom image classes. To do that create the following folders on your local computer.

---images (top level folder and following sub folders)
    
    --- test
          --- car
                car1.jpg
                car2.jpg

          --- ship
                ship1.jpg
                ship2.jpg

          --- helicopter
                hc1.jpg
                hc2.jpg
    --- train
            --- car
                car1t.jpg
                car2t.jpg
                car3t.jpg
                car4t.jpg
                car5t.jpg

          --- ship
                ship1t.jpg
                ship2t.jpg
                ship3t.jpg
                ship4t.jpg
                ship5t.jpg

          --- helicopter
                hc1t.jpg
                hc2t.jpg
                hc3t.jpg
                hc4t.jpg
                hc5t.jpg

    --- valid
            --- car
                car1v.jpg
                car2v.jpg

          --- ship
                ship1v.jpg
                ship2v.jpg

          --- helicopter
                hc1v.jpg
                hc2v.jpg

Then put the "images" folder into a zip. Name it like images.zip

## Note
You can use any number of classes and name them as you want. Images filenames are also not important. You can have any names. Just make sure that you have different images in test, train and valid folders.

After that go here;
http://<www.yourdomain.com>/easy-image-classify/

select your images' zip file and click 'Upload, ReModel and Run'
After submitting the request your model will be ready after some time. It depends on your classes and number of images.

## INFERENCE
Browse this link
http://<www.yourdomain.com>/easy-image-classify/index.php?opt=inf_form

You will see an screen like this;

![Create Model](https://raw.githubusercontent.com/theprodev/easy-image-classify/master/screenshots/inference.jpg)

Now select any image from your computer and click the 'Uplaod & Check' button. You will see a result about the class of this image according your data set.

Note: This scripts uses the free tier of image classification service by [Everlive.net](https://www.everlive.net/artificial-intelligence/25-computer-vision/51-online-image-classification-service.html) which will begin to work from Oct 20, 2019. So this script will work after that date.

Your Feedback is Welcome!



