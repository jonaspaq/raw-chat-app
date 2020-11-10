<template>
<div class="col-md-8">
    <div class="settings-tray">
        <div class="friend-drawer no-gutters friend-drawer--grey">
        <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
        <div class="text">
            <h6 v-if="isTrayInfoEmpty()">{{ trayTitle }}</h6>
            <p class="text-muted"> is online </p>
        </div>
        <span class="settings-tray--right">
            <i class="material-icons"></i>
            <i class="material-icons"></i>
            <i class="material-icons"></i>
        </span>
        </div>
    </div>
    <div class="chat-panel" ref="chatPanel">
        <div v-for="message in messages" :key="message.id" class="row no-gutters">
            <!-- <div :class="(checkIfAuthUser(message.user_id)) ? '':'col-md-3'" > -->
                <div :class="[(checkIfAuthUser(message.user_id)) ? 'chat-bubble--right':'chat-bubble--left']" class="chat-bubble">
                {{ message.body }}
                </div>
            <!-- </div> -->
        </div>
    </div>

    <form class="row p-0 m-0" method="post" @submit.prevent="sendMessage()">
        <input v-model="textMessage" class="form-control col-10" type="text" placeholder="Enter text here. . .">
        <button type="submit" class="btn btn-primary col-2"> Send </button>
    </form>
</div>
</template>

<script>
export default {
    name: 'MessageTray',
    props: ['user'],

    mounted() {
        this.$root.$on('chooseThread', data => {
            this.setTrayInfoData(data)
        })
    },

    data: () => ({
        trayInfo: {
            trayTitle: null,
            trayData: null
        },
        messages: null,
        textMessage: null,
    }),
    methods: {
        isTrayInfoEmpty() {
            return (this.trayInfo == null || this.trayInfo.length == 0) ? false : true
        },
        setTrayInfoData(data) {
            let self = this
            let members = data.thread_members
            let nonAuthUser = members.filter(function(members) {
                return members.id != self.user.id
            })

            // Check if the thread is a group and set proper tray title
            if(data.thread_group_details == undefined || data.thread_group_details === null) {
                self.trayInfo.trayTitle = nonAuthUser[0].user.name
            } else {
                self.trayInfo.trayTitle = data.thread_group_details.group_name
            }

            // Set thread details to tray data
            self.trayInfo.trayData = data

            // Load messages of this thread
            self.loadMessages();
        },
        loadMessages() {
          let thread = this.trayInfo.trayData

          axios.get('/thread/messages/' + thread.id)
          .then((res) => {
            this.messages = res.data

            // Scroll down chat body when sending new message
            setTimeout(e => {this.$refs.chatPanel.scrollTop = this.$refs.chatPanel.scrollHeight}, 1)
          })
          .catch((err) => {
              console.log(err)
          })
        },
        sendMessage() {
            let holder = {
                body: this.textMessage,
                user_id: this.user.id,
                thread: this.trayInfo.trayData.id
            }

            this.messages.push(holder)
            this.textMessage = ''

            // Scroll down chat body when sending new message
            setTimeout(e => {this.$refs.chatPanel.scrollTop = this.$refs.chatPanel.scrollHeight}, 1)

            axios.put('/thread/messages/create', holder)
            .catch((err) => {
                console.log(err)
            })
        },
        checkIfAuthUser(number) {
            return (number == this.user.id) ? true : false
        }
    },
    computed: {
        trayTitle(){
            return this.trayInfo.trayTitle
        }
    }

}
</script>

<style lang="scss" scoped>
$blue: #4e9be8;

.profile-image {
  width: 50px;
  height: 50px;
  border-radius: 40px;
}

.settings-tray {
  background: #eee;
  padding: 10px 15px;
  border-radius: 7px;

  .no-gutters {
    padding: 0;
  }

  &--right {
    float: right;

    i {
      margin-top: 10px;
      font-size: 25px;
      color: grey;
      margin-left: 14px;
      transition: .3s;

      &:hover {
        color: $blue;
        cursor: pointer;
      }
    }
  }
}

.friend-drawer {
  padding: 10px 15px;
  display: flex;
  vertical-align: baseline;
  background: #fff;
  transition: .3s ease;

  &--grey {
    background: #eee;
  }

  .text {
    margin-left: 12px;
    width: 70%;

    h6 {
      margin-top: 6px;
      margin-bottom: 0;
    }

    p {
      margin: 0;
    }
  }

  .time {
    color: grey;
  }

  &--onhover:hover {
    background: $blue;
    cursor: pointer;

    p,
    h6,
    .time {
      color: #fff !important;
    }
  }
}

.chat-panel {
    width: 100%;
    // display: block;
    overflow: hidden;
    overflow-y: auto;
    max-height: 400px;
}

.chat-bubble {
    max-width: 250px;
    padding: 10px 14px;
    background: #eee;
    margin: 10px 30px;
    border-radius: 9px;
    position: relative;
    animation: fadeIn 1s ease-in;

//   &:after {
//     content: '';
//     position: absolute;
//     top: 50%;
//     width: 0;
//     height: 0;
//     border: 20px solid transparent;
//     border-bottom: 0;
//     margin-top: -10px;
//   }

//   &--left {
//      &:after {
//       left: 0;
//       border-right-color: #eee;
// 	    border-left: 0;
//       margin-left: -20px;
//     }
//   }

  &--right {
    margin-left: auto;
    background: $blue;
    color: white;
  }
}

.offset-md-9 {
  .chat-bubble {
    background: $blue;
    color: #fff;
  }
}
</style>
