<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Alexey Plekhanov" />
    <meta name="description" content="Where to lunch today in Wroclaw?" />
    <meta id="token" name="token" data-value="{{ csrf_token() }}">

    <title>Where to lunch today?</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            color: #333;
            display: table;
            font-weight: 100;
            font-family: 'Open Sans', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 60px;
            margin-bottom: 40px;
        }

        ul.all-places {
            padding: 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="content">
            <div class="title">{{ $place }}</div>
        </div>

        <div>
            <form id="new-place" @submit.prevent="submitNewPlace">
                <label for="place">Suggest new place</label>
                <br>
                <input type="text" name="place" id="place" v-model="newPlaceForm.place" required>
                <br>
                <input type="submit">
                <i class="fa fa-spinner fa-spin" v-if="newPlaceForm.submitting"></i>
            </form>
        </div>

        <div>
            <ul class="all-places">
                @foreach($places as $place)
                    <li>{{ $place }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.11/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.17/vue-resource.min.js"></script>

    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('data-value');

        var vm = new Vue({
            el: '.container',
            data: {
                newPlaceForm: {
                    place: '',
                    submitting: false
                }
            },
            methods: {
                submitNewPlace: function(e) {
                    this.newPlaceForm.submitting = true;
                    this.$http
                        .post('places', this.newPlaceForm, function(data, status, request) {
                            location.reload();
                        }.bind(this))
                        .error(function(data, status, request) {
                            location.reload();
                        });
                }
            }
        });
    </script>

</body>
</html>