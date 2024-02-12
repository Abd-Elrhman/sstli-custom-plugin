<?php
/*
Template Name: الفروع
*/

get_header(); // Include header.php
?>

<div id="primary" class="content-area container">
    <main id="main" class="site-main">

        <header class="page-header">
            <h1 class="page-title"><?php echo esc_html__('الفروع', 'sstlicustom'); ?></h1>
        </header>

        <?php
        // The WordPress loop
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>

        <div class="branches-archive">
            <?php
            // Fetch and display branches
            $branches = sstli_get_branches();
            foreach ($branches->data as $branch) :
                $branch_permalink = get_permalink($branch['id']);
                ?>
                <a href="<?php echo esc_url($branch_permalink); ?>" class="branch-link">
                    <article id="post-<?php echo esc_attr($branch['id']); ?>" <?php post_class('branch-archive-item'); ?>>
                        <header class="entry-header">
                            <h2 class="entry-title"><?php echo esc_html($branch['title']); ?></h2>
                        </header>
                        <div class="entry-content">
                            <div class="branch-thumbnail">
                                <?php
                                $image_url = is_array($branch['branch_image']) ? $branch['branch_image']['url'] : $branch['branch_image'];
                                $alt_text = esc_attr($branch['title']);

                                if ($image_url) {
                                    ?>
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo $alt_text; ?>">
                                    <?php
                                } else {
                                    echo esc_html__('Image not found', 'your-text-domain');
                                }
                                ?>
                            </div>
                            <div class="branch-details">
                                <p><strong><?php echo esc_html__('اسم الفرع:', 'sstlicustom'); ?></strong> <?php echo esc_html($branch['branch_name']); ?></p>
                                <p><strong><?php echo esc_html__('الخط الساخن:', 'sstlicustom'); ?></strong> <?php echo esc_html($branch['branch_hotline']); ?></p>
                                <p><strong><?php echo esc_html__('البريد الإلكتروني:', 'sstlicustom'); ?></strong> <?php echo esc_html($branch['branch_email']); ?></p>
                                <p><strong><?php echo esc_html__('الرقم:', 'sstlicustom'); ?></strong> <?php echo esc_html($branch['branch_number']); ?></p>
                                <p>
                                    <strong><?php echo esc_html__('الموقع:', 'sstlicustom'); ?></strong>
                                    <?php
                                    $location_url = esc_url($branch['branch_location']);
                                    if ($location_url) {
                                        ?>
                                        <a href="<?php echo $location_url; ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo esc_html__('رؤية العنوان', 'sstlicustom'); ?>
                                        </a>
                                        <?php
                                    } else {
                                        echo esc_html__('لا يوجد رابط للعنوان', 'sstlicustom');
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </article>
                </a>
            <?php endforeach; ?>
        </div>

    </main>
</div>

<?php get_footer(); // Include footer.php ?>