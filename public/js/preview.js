if(document.querySelector('#img') != null) {
    var src = document.querySelector('#img').getAttribute('src');
    $('[type="file"]').on('change', function () {
        if (this.files[0] != undefined) {
            var imgValue = this.value;
            //regExp for detection images
            var regExp = /^.*\.(?:jpg|jpeg|png|gif)\s*$/ig;
            var validation = imgValue.search(regExp);
            if (validation != 0) {
                this.value = '';
            }

            //add image to preview
            var img = this.files[0];
            var preview = document.querySelector('#img');
            var reader = new FileReader();
            reader.onload = function () {
                preview.src = reader.result;
            }
            reader.readAsDataURL(img);
        }
        else {
            document.querySelector('#img').setAttribute('src', src);
        }
    });
}
