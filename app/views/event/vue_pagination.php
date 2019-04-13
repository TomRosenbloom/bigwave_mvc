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
<script src="<?= URL_ROOT ?>/js/paginator.js"></script>

<script>
let page = 1;
let total = <?php echo $data['total']; ?>;
let paginator = new Paginator(page, 5, total);

let offset = paginator.getOffset();


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
// how do we get the offset and limit?
// I think I need to create a js paginator class...
// But we still need a way to get pagination vars from php to js i.e. so we can set
// items per page, say, in the php mvc controller (I think)
// ...but let's hard code them for now
var url = 'http://localhost.bigwavemvc/api/getPage?offset=' + offset + '&limit=' + paginator.perPage;

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