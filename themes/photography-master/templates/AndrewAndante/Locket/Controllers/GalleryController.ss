<!DOCTYPE html>
<% include Header %>

<body>
<% include NavBar %>

<div class="canvas gallery"><br>
    <% if $CurrentAlbumID %>
        <h1 class="blog-post-title text-center">$CurrentAlbumTitle</h1>
    <% else %>
        <h1 class="blog-post-title text-center">Choose an Album</h1>
    <% end_if %>
    <span class="title-divider"></span>
    <div id="container" class="container">
        <div id="gallery">
            <% if $CurrentAlbumID %>
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
            <% else %>
                <div id="gallery-header">
                    <div id="gallery-header-center">
                        <div id="gallery-header-center-left">
                            <div id="gallery-header-center-left-title">
                                <h2>Choose an album:</h2>
                            </div>
                        </div>
                    </div>
                    <div id="gallery-header-center-right">
                        <div id="gallery-header-center-right-links">
                        <ul style="clear: both; font-size: 32px; padding: 10px; margin: 10px; list-style-type: none;">
                            <% loop $AllAlbums %>
                            <li style="padding: 10px;"><a href="{$Top.AbsoluteURL}?album=$ID">$CleanTitle</a></li>
                            <% end_loop %>
                        <li style="padding: 10px;"><a href="{$Top.AbsoluteURL}?album=all">View all</a></li>
                        </ul>
                    </div>
                </div>
            <% end_if %>
        </div>
    </div>
</div>
</body>
