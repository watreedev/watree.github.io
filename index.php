<?php
session_start();
include('db.php');
// Giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Rastgele videoları seçme
$stmt = $conn->prepare("SELECT video_id, title, description, filename FROM videos ORDER BY RAND() LIMIT 5");
$stmt->execute();
$stmt->bind_result($video_id, $title, $description, $filename);
$videos = [];
while ($stmt->fetch()) {
    $videos[] = ['video_id' => $video_id, 'title' => $title, 'description' => $description, 'filename' => $filename];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Anasayfa</title>
</head>
<body>
    <h1>Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <nav>
        <a href="profile.php">Profil</a>
        <a href="logout.php">Çıkış Yap</a>
    </nav>
    <h2>Rastgele Videolar</h2>
    <?php foreach ($videos as $video): ?>
        <div>
            <h3><?php echo htmlspecialchars($video['title']); ?></h3>
            <p><?php echo htmlspecialchars($video['description']); ?></p>
            <video width="320" height="240" controls>
                <source src="uploads/<?php echo htmlspecialchars($video['filename']); ?>" type="video/mp4">
                Tarayıcınız video etiketini desteklemiyor.
            </video>
            <a href="view_video.php?video_id=<?php echo $video['video_id']; ?>">İzle</a>
        </div>
    <?php endforeach; ?>
</body>
</html>
