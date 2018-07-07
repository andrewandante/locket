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
                        <div id="gallery-header-center-pagination">
<% if $AllImages.MoreThanOnePage %>
                        <% if $AllImages.NotFirstPage %>
                            <a class="prev" href="$AllImages.PrevLink">Previous</a>
                        <% end_if %>
                        <% loop $AllImages.Pages %>
                            <% if $CurrentBool %>
                                $PageNum
                            <% else %>
                                <% if $Link %>
                                    <a href="$Link">$PageNum</a>
                                <% else %>
                                    ...
                                <% end_if %>
                            <% end_if %>
                        <% end_loop %>
                        <% if $AllImages.NotLastPage %>
                            <a class="next" href="$AllImages.NextLink">Next</a>
                        <% end_if %>
                    <% end_if %>
</div>
                    </div>
                </div>
            </div>
            <div id="gallery-content">
                <div id="gallery-content-center">
                    <% loop $AllImages %>
                        <a href="{$Resampled.URL}" data-lightbox="studio1" data-title="$Title: $DisplayDate.Date">
                            <img src="{$Resampled.URL}" class="all {$DisplayDate.Year}"/>
                        </a>
                    <% end_loop %>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
