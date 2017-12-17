<!DOCTYPE html>
<% include Header %>

<body>
<% include NavBar %>

<div class="canvas gallery"><br>
    <h1 class="blog-post-title text-center">Gallery of Images</h1>
    <span class="title-divider"></span>
    <div id="container" class="container">
        <div id="gallery">
            <div id="gallery-header">
                <div id="gallery-header-center">
                    <div id="gallery-header-center-left">
                        <div id="gallery-header-center-left-title">All Galleries</div>
                    </div>
                    <div id="gallery-header-center-right">
                        <div class="gallery-header-center-right-links" id="filter-all">All</div>
                        <% loop $Years %>
                            <div class="gallery-header-center-right-links" id="filter-$Year">$Year</div>
                        <% end_loop %>
                    </div>
                </div>
            </div>
            <div id="gallery-content">
                <div id="gallery-content-center">
                    <% loop $AllImages %>
                        <a href="{$Link}" data-lightbox="studio1" data-title="$Title: $Created.Format('d/M/Y')">
                            <img src="{$Link}" class="all {$Created.Year}"/>
                        </a>
                    <% end_loop %>
                </div>
            </div>
        </div>
    </div>
</div>
</body>