<template>
<div class="chat-user-list">
    <div class="search-box">
        <div class="input-wrapper">
        <i class="material-icons"></i>
        <input v-model="toSearch" @keyup="search()" placeholder="Search here" type="text">
        </div>
    </div>

    <div class="w-100" v-if="checkIfEmpty(searchContacts)">
        <i class="threadCategory">Contacts</i>
        <div v-for="thread in searchContacts" :key="thread.id" class="friend-drawer friend-drawer--onhover">
            <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
            <div class="text">
                <h6> {{ getSecondUserName(thread) }} </h6>
                <p class="text-muted">Hey, you're arrested!</p>
            </div>
            <span class="time text-muted small">13:21</span>
        </div>
    </div>

    <div class="w-100" v-if="checkIfEmpty(searchGroupThreads)">
        <i class="threadCategory">Group Conversations</i>
        <div v-for="thread in searchGroupThreads" :key="thread.id" class="friend-drawer friend-drawer--onhover">
            <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
            <div class="text">
                <h6> {{ thread.thread_group_details.group_name }} </h6>
                <p class="text-muted">Hey, you're arrested!</p>
            </div>
            <span class="time text-muted small">13:21</span>
        </div>
    </div>

    <div class="w-100" v-if="checkIfEmpty(searchUnrelatedThreads)">
        <i class="threadCategory">More people</i>
        <div v-for="thread in searchUnrelatedThreads" :key="thread.id" @click="chooseCreateThread(thread)" class="friend-drawer friend-drawer--onhover">
            <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
            <div class="text">
                <h6> {{ thread.name }} </h6>
                <p class="text-muted">Hey, you're arrested!</p>
            </div>
            <span class="time text-muted small">13:21</span>
        </div>
    </div>

    <div class="w-100">
        <i class="threadCategory">Default thread</i>
        <div v-for="(thread, index) in messageThreads" :key="index" @click="chooseThread(thread)" class="friend-drawer friend-drawer--onhover">
            <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
            <div class="text">
                <!-- show this title if it is a group thread -->
                <h6 v-if="checkIfEmpty(thread.thread_group_details)"> {{ thread.thread_group_details.group_name }} </h6>
                <!-- show this title if it is a person to person thread -->
                <h6 v-if="!checkIfEmpty(thread.thread_group_details)"> {{ getSecondUserName(thread) }} </h6>
                <p class="text-muted">Hey, you're arrested!</p>
            </div>
            <span class="time text-muted small">13:21</span>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'MessageThreads',
    props: ['user'],
    created() {
        this.fetchThreads();
    },
    data() {
        return {
            messageThreads: null,
            toSearch: null,
            searchContacts: null,
            searchGroupThreads: null,
            searchUnrelatedThreads: null,
        }
    },
    methods: {
        fetchThreads() {
            axios.get('/message/threads')
            .then((response) => {
                this.messageThreads = response.data
            })
            .catch((err) => {
                console.log(err)
            })
        },
        search() {
            let toSearch = this.toSearch

            axios.post('/search/thread', {toSearch})
            .then((res) => {
                this.searchContacts = null
                this.searchContacts = res.data
            })
            .catch((err) => {
                console.log(err)
            })

            axios.post('/search/connection', {toSearch})
            .then((res) => {
                // console.log(res.data)
                this.searchUnrelatedThreads = null
                this.searchUnrelatedThreads = res.data
            })
            .catch((err) => {
                console.log(err)
            })

            axios.post('/search/thread/group', {toSearch})
            .then((res) => {
                this.searchGroupThreads = null
                this.searchGroupThreads = res.data
            })
            .catch((err) => {
                console.log(err)
            })
        },
        checkIfEmpty(data) {
            return (data == null || data.length == 0) ? false : true
        },
        chooseThread(data) {
            this.$root.$emit('chooseThread', data)
        },
        chooseCreateThread(data) {
            this.nullifySearch()

            axios.post('/thread/create', data)
            .then((res) => {
                // console.log(res.data)
                this.$root.$emit('chooseThread', res.data)
            })
            .catch((err) => {
                console.log(err)
            })
        },
        getSecondUserName(data) {
            // This checks which name is to set as title in message thread list for person to person
            return (data.thread_members[0].user.id == this.user.id) ? data.thread_members[1].user.name : data.thread_members[0].user.name
        },
        nullifySearch() {
            this.toSearch = null
            this.searchContacts = null
            this.searchGroupThreads = null
            this.searchUnrelatedThreads = null
        }
    }
}
</script>

<style scoped lang="scss">
$blue: #74b9ff;

.chat-user-list {
    display: block;
    overflow: hidden;
    overflow-y: auto;
    max-height: 660px;
}

.threadCategory {
    // border: 1px solid red;
    padding: 0 15px;
    font-weight: 600;
    font-size: 14px;
    font-style: normal;
    color: #616161;
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

.profile-image {
  width: 50px;
  height: 50px;
  border-radius: 40px;
}

.search-box {
  background: #fafafa;
  padding: 10px 13px;

  .input-wrapper {
    background: #fff;
    border-radius: 40px;

    i {
      color: grey;
      margin-left: 7px;
      vertical-align: middle;
    }
  }
}

input {
  border: none;
  border-radius: 30px;
  width: 80%;

  &::placeholder {
    color: #e3e3e3;
    font-weight: 300;
    margin-left: 20px;
  }

  &:focus {
    outline: none;
  }
}

</style>
