<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="jumbotron jumbotron-flud text-center">
    <div class="container">
        <h1 class="display-3"><?= $data['title']; ?></h1>
        <p class="lead"><?= $data['description']; ?></p>
    </div>
</div>

<div id="hello">
    <say-hello></say-hello>
</div>


<div id="listItems" style="height: 300px; overflow: scroll;">
    <div id="events">
        <event-detail
            v-for = "event in events"
            v-bind:event="event"
            v-bind:key="event.id"
        ></event-detail>
    </div>
</div>


<div class="" id="listContainer">
    <div id="listItems" style="height: 600px; overflow: scroll;">
        <div class="" id="event_" v-for="event in events">
            <h3>{{ event.title }}<small class="float-right">{{ event.name }}</small></h3>
            <p>Date: {{ event.date }}</p>
            <p>{{ event.description }}</p>
        </div>
    </div>
</div>


<div id="app-3">
  <span v-if="seen">Now you see me</span>
</div>

<div id="app-5">
  <p>{{ message }}</p>
  <button v-on:click="reverseMessage">Reverse Message</button>
</div>

<div id="reverse">
    <p></p>
    <button>Reverse</button>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>

Vue.component('event-detail', {
  props: ['event'],
  template: '<div><h3>{{ event.title }}</h3></div>'
})

var url = 'http://localhost.bigwavemvc/api/getPage?offset=0&limit=5';

var eventList = new Vue({
    el: '#events',
//    data: {
//        events: [
//            { id: 0, title: 'Mayor of London\'s Sky Ride' },
//            { id: 1, title: 'Sky Ride Glasgow' },
//            { id: 2, title: 'Sky Ride Birmingham' }
//        ]
//    }    
    data: {
        events: []
    },
    mounted(){
        axios.get(url).then(response => {
            this.events = response.data;
        });
    }
});

</script>


<script>
//Vue.config.devtools = true;
    
var app = new Vue({
    el: '#listContainer',
    data: {
        events: [
            { title: 'Mayor of London\'s Sky Ride' },
            { title: 'Sky Ride Glasgow' },
            { title: 'Sky Ride Birmingham' }
        ]
    }
})
    
var app3 = new Vue({
  el: '#app-3',
  data: {
    seen: true
  }
})

var app5 = new Vue({
  el: '#app-5',
  data: {
    message: 'Hello Vue.js!'
  },
  methods: {
    reverseMessage: function () {
      this.message = this.message.split('').reverse().join('')
    }
  }
})


$(document).ready(function(){
    $("#reverse p").html("Hello");
    $("#reverse button").click(function(){
        $("#reverse p").html($("#reverse p").html().split('').reverse().join(''));
    })
});

Vue.component('say-hello', {
  template: '<p>Hello</p>'
})
var hello = new Vue({
    el: '#hello'
})
</script>