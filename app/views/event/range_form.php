<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h2>Find events in range</h2>
            <div class="row">
                <div class="w-100" id="form">
                    <form class="" action="" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-8" id="postcode-input">
                                <label for="postcode">Postcode: </label>
                                <input class="form-control" type="text" name="postcode" value="" placeholder="enter a valid UK postcode">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8" id="range-input">
                                <label for="range">Range: </label>
                                <input class="form-control" type="text" name="range" value="<?= $data['range']; ?>" placeholder="enter a range in km">
                            </div>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>

                </div>

            </div>

        </div>


    </body>
</html>
