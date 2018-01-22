<div id="myCarousel" class="canvas carousel slide" data-ride="carousel">
    <!-- Full Page Image Background Carousel Header -->

    <!-- Indicators -->
    <ol class="carousel-indicators xtra-border">
        <% loop $CarouselImages %>
        <li data-target="#myCarousel" data-slide-to="$Position" <% if $First %>class="active"<% end_if %>></li>
        <% end_loop %>
    </ol>

    <!-- Wrapper for Slides -->
    <div class="carousel-inner" role="listbox">
        <% loop $CarouselImages %>
        <div class="item <% if $First %>active<% end_if %>">
            <img src="$Link" alt="$Position slide">
            <div class="carousel-caption">
                <h3 class="title-home">$Title</h3>
                <h4 class="date-home">$DisplayDate.Date</h4>
            </div>
        </div>
        <% end_loop %>
    </div>
</div>