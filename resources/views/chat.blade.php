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
    <title>Chat</title>
</head>

<body>
    <audio src="/js/beep.wav" id="beepp" style="visibility: hidden;"></audio>
    <div class="cntt" id="vapp">
        <div>
            <div class="topname">
                <div  ><span v-if="selectedUser">@{{selectedUser.Name}}</span></div>
                <button v-if="callEnabled" @click="onCallButton()"><i class="mi">call</i></button>
                <button v-if="callEnabled && callStarted" @click="onEndCallButton()"><i class="mi">call_end</i></button>
                <div class="dropdown" v-if="notifs">
                    <span v-if="newNotif" class="mi" style="color: red; font-size: 0.8em; transform: translate(31px, -9px);">fiber_manual_record</span>
                    <button @click="onNotifClick()" class="dropbtn"><i class="mi">notifications</i></button>

                    <div class="dropdown-content">
                        <a href="#" v-for="n in notifs" :class="[n.new?'newnotif':'notifold']" @click="n.new=false">@{{n.message}}</a>
                    </div>
                </div>
            </div>
            <div v-if="callEnabled">
                <video id="received_video" autoplay height="100" width="300"></video>
                <video id="local_video" autoplay muted height="100" width="300"></video>
            </div>
        </div>
        <div>
            <div v-for="i in users" :class="{'item-selectes':i.selected}" @click="userClick(i)">@{{i.Name}}</div>
        </div>
        <div id="scrollcont">
            <div class="msgcont" v-if="chats">
                <div v-for="i in chats" :class="{msgfrom:i.isFrom,msgme:!i.isFrom}">
                    <div>@{{i.Time}}</div>
                    <div v-if="i.Message">@{{i.Message}}</div>
                    <div v-if="i.FileName">
                        <div v-if="isImage(i.FileName)">
                            <img style="max-width:400px" :src="'/files/'+i.FileName" />
                        </div>
                        <div v-if="!isImage(i.FileName)">
                            <a :href="'/files/'+i.FileName">@{{i.FileName}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:flex;padding:1em">
            <textarea type="text" style="flex:1;margin-right:1em" class="form-control" v-model="message" @keypress="onKeyPress($event)"></textarea>
            <div class="upload-btn-wrapper" style="height:2.5em;align-self:center">
                <button class="btn btn-outline-info" style="border:none"><i class="mi">attachment</i></button>
                <input type="file" name="myfile" @change="onFileChange($event)" />
            </div>
            <button class="btn btn-primary" style="flex:.1;height:2.5em;align-self:center" @click="sendClick()"><i class="mi">send</i></button>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.global.prod.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" type="module" src="{{ asset('js/chat.js') }}"></script>
</body>

</html>