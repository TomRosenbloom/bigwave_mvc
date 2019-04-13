<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container">
        
    <?php require APP_ROOT . '/views/inc/pagination.php'; ?>

    <div id="listItems">
        <div id="events">
            <event-detail
                v-for = "event in events"
                v-bind:event="event"
                v-bind:key="event.id"
            ></event-detail>
        </div>
    </div>
    
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
/////////////////////////////
// create pagination links

var paginationLinks = new Vue({
    el: '#pagination-links',
    data: {

    },
    methods: {
        goToPage: function(){
            //this.link = 
            // so the app/function will need to be per link, not for the whole pagination?
            alert('foo');
        }
    }
});

/////////////////////////////
// list this page of events

// template for displaying an event
Vue.component('event-detail', {
  props: ['event'],
  template: '<div><h3>{{ event.title }}<small class="float-right">{{ event.feed_name }}</small></h3>\n\
<p>Date: {{ event.event_date }}</p>\n\
<p>{{ event.description }}</p>\n\
</div>'
});

// url to fetch page of events from local API
var url = 'http://localhost.bigwavemvc/api/getPage?offset=0&limit=5';

// list the events in the current page
var eventList = new Vue({
    el: '#events', 
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