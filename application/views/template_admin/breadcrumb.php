<div class="section-header">
    <h1><?php echo $breadcrumb['header_content']; ?></h1>
    <div class="section-header-breadcrumb">
        <?php
        $str = "<div class='breadcrumb-item'><a href='" . base_url() . "dashboard'>Dashboard </a></div>";
        foreach ($breadcrumb['breadcrumb_link'] as $key => $row) {
            if ($row['link']) {
                $str .= "<div class='breadcrumb-item'><a href='" . $row['url'] . "'>" . $row['content'] . "</a></div>";
            } else {
                $str .= "<div class='breadcrumb-item'>" . $row['content'] . "</div>";
            }
        ?>
        <?php
        }
        echo $str;
        ?>
    </div>
</div>