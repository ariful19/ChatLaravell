<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/site.css') }}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>

    </style>
    <title>Notify</title>
</head>

<body>
    <div style="    display: flex;    justify-content: center;    align-items: center;    height: 95vh" id="vapp">
        <div>
            <div>
                <div v-for="i in users" :class="{'item-selectes':i.selected}" @click="userClick(i)">@{{i.Name}}</div>
            </div>
            <div>
                <input type="text" v-model="message" />
                <button class="btn btn-primary" style="flex:.1;height:2.5em;align-self:center" @click="sendClick()"><i class="mi">send</i></button>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.global.prod.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" type="module" src="{{ asset('js/notify.js') }}"></script>
</body>

</html>