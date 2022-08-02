
var ws;
var myConnectionId = 0;
function initWebSocket() {
    return new Promise(function (resolve, reject) {
        ws = new WebSocket('ws://localhost:8001');
        ws.addEventListener('open', function open() {
            ws.send(JSON.stringify({ type: 'connectionId' }));
        });
        ws.addEventListener('message', function message(data) {
            var d = JSON.parse(data.data);
            switch (d.type) {
                case "connectionId":
                    myConnectionId = d.id;
                    resolve(myConnectionId);
                    break;
                case "userUpdated":
                    app.GetUsers();
                    break;
                default:
                    break;
            }
        });
    });
}

const { createApp } = Vue;

function log(text) {
    var time = new Date();

    console.log("[" + time.toLocaleTimeString() + "] " + text);
}



window['app'] = createApp({
    data() {
        return {
            message: '',
            users: null,
            chats: null,
            selectedUser: null,
            callStarted: false,
            callEnabled: false
        }
    },
    async mounted() {
        var cid = await initWebSocket();
        await this.GetUsers();
    },
    methods: {
        async GetUsers() {
            var res = await fetch("/Chat/GetUsers");
            var data = await res.json();
            data.forEach(o => o["selected"] = false);
            if (this.selectedUser) {
                this.selectedUser = data.find(o => o.id == this.selectedUser.id);
                this.selectedUser.selected = true;
            }
            this.users = data;
        },
        async userClick(i) {
            this.users.forEach(o => o["selected"] = false);
            i.selected = true;
            this.selectedUser = i;
            console.log(this.selectedUser);
        },
        sendClick() {
            var to = this.selectedUser;
            if (!to) return;
            ws.send(JSON.stringify({ type: 'notification', message: this.message, toClientId: to.ConnectinId, toUserId: to.Id }));
            this.message = "";
        }
    }
}).mount('#vapp');
