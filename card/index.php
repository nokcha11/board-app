<?php
$cards = [
    [
        'no' => '001',
        'title' => 'LUMINA',
        'subtitle' => 'Glass Jeumorism Series',
        'type' => 'CLARITY',
        'attribute' => 'Light Attribute',
        'desc' => '투명한 마음은 빛을 담아, 모든 것을 비추고 연결한다.',
        'shape' => 'orb',
        'energy' => 4,
        'harmony' => 5,
    ],
    [
        'no' => '002',
        'title' => 'AURORA',
        'subtitle' => 'Iridescent Dream Card',
        'type' => 'FLOW',
        'attribute' => 'Wave Attribute',
        'desc' => '부드러운 흐름 속에서 새로운 가능성이 반짝인다.',
        'shape' => 'ribbon',
        'energy' => 5,
        'harmony' => 4,
    ],
    [
        'no' => '003',
        'title' => 'SERENE',
        'subtitle' => 'Soft Crystal Edition',
        'type' => 'CALM',
        'attribute' => 'Mist Attribute',
        'desc' => '고요한 안개처럼 감싸며 감정의 균형을 회복한다.',
        'shape' => 'crystal',
        'energy' => 3,
        'harmony' => 5,
    ],
    [
        'no' => '004',
        'title' => 'PRISM',
        'subtitle' => 'Pastel Spectrum Card',
        'type' => 'COLOR',
        'attribute' => 'Prism Attribute',
        'desc' => '작은 빛도 여러 색의 감각으로 확장된다.',
        'shape' => 'diamond',
        'energy' => 5,
        'harmony' => 3,
    ],
    [
        'no' => '005',
        'title' => 'NOVA',
        'subtitle' => 'Luminous Glass Token',
        'type' => 'SPARK',
        'attribute' => 'Star Attribute',
        'desc' => '눈부신 시작은 아주 작은 반짝임에서 태어난다.',
        'shape' => 'star',
        'energy' => 4,
        'harmony' => 4,
    ],
    [
        'no' => '006',
        'title' => 'ECHO',
        'subtitle' => 'Transparent Memory Card',
        'type' => 'MEMORY',
        'attribute' => 'Echo Attribute',
        'desc' => '겹겹이 쌓인 빛의 잔상이 장면을 오래 머물게 한다.',
        'shape' => 'bubble',
        'energy' => 3,
        'harmony' => 4,
    ],
];

function dots($count) {
    $html = '';
    for ($i = 0; $i < 5; $i++) {
        $html .= '<span class="dot ' . ($i < $count ? 'on' : '') . '"></span>';
    }
    return $html;
}

function stars($count) {
    $html = '';
    for ($i = 0; $i < 5; $i++) {
        $html .= '<span class="star ' . ($i < $count ? 'on' : '') . '">✦</span>';
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glass Jeumorism Cards</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --text-main: rgba(255, 255, 255, 0.96);
            --text-sub: rgba(255, 255, 255, 0.78);
            --text-soft: rgba(255, 255, 255, 0.66);
            --glass-line: rgba(255, 255, 255, 0.52);
        }

        body {
            min-height: 100vh;
            font-family: 'Pretendard', 'Noto Sans KR', 'Apple SD Gothic Neo', Arial, sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at 12% 8%, rgba(127, 92, 255, 0.95), transparent 28%),
                radial-gradient(circle at 82% 14%, rgba(255, 109, 222, 0.72), transparent 28%),
                radial-gradient(circle at 22% 82%, rgba(50, 205, 255, 0.42), transparent 32%),
                radial-gradient(circle at 74% 74%, rgba(140, 73, 255, 0.58), transparent 30%),
                linear-gradient(135deg, #14051f 0%, #2a1044 34%, #35155f 66%, #170724 100%);
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            width: 520px;
            height: 520px;
            border-radius: 999px;
            filter: blur(34px);
            opacity: 0.55;
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            top: -170px;
            left: -130px;
            background: conic-gradient(from 120deg, #7df5ff, #9e7cff, #ff8bea, #7df5ff);
        }

        body::after {
            right: -170px;
            bottom: -170px;
            background: conic-gradient(from 20deg, #ffb8f6, #7beeff, #a98bff, #ffb8f6);
        }

        .wrap {
            position: relative;
            z-index: 1;
            max-width: 1440px;
            margin: 0 auto;
            padding: 72px 28px 90px;
        }

        .hero {
            text-align: center;
            margin-bottom: 46px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            margin-bottom: 18px;
            border: 1px solid rgba(255, 255, 255, 0.42);
            border-radius: 999px;
            color: rgba(255, 255, 255, 0.92);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.42), 0 18px 42px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        h1 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: clamp(42px, 8vw, 96px);
            letter-spacing: 0.16em;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.98);
            text-shadow: 0 0 18px rgba(255, 255, 255, 0.38), 0 8px 42px rgba(185, 111, 255, 0.5);
        }

        .hero p {
            margin-top: 14px;
            color: rgba(255, 255, 255, 0.78);
            font-size: clamp(16px, 2vw, 22px);
            text-shadow: 0 2px 18px rgba(0, 0, 0, 0.22);
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(260px, 1fr));
            gap: 30px;
        }

        .card {
            position: relative;
            min-height: 610px;
            padding: 26px;
            border-radius: 36px;
            overflow: hidden;
            isolation: isolate;
            background:
                linear-gradient(145deg, rgba(255, 255, 255, 0.26), rgba(255, 255, 255, 0.075) 48%, rgba(143, 221, 255, 0.12)),
                radial-gradient(circle at 18% 8%, rgba(255, 255, 255, 0.7), transparent 16%),
                radial-gradient(circle at 88% 18%, rgba(255, 168, 243, 0.32), transparent 25%),
                radial-gradient(circle at 48% 36%, rgba(109, 243, 255, 0.22), transparent 30%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow:
                inset 0 0 0 1px rgba(255, 255, 255, 0.28),
                inset 0 2px 14px rgba(255, 255, 255, 0.45),
                inset 0 -34px 70px rgba(125, 205, 255, 0.12),
                0 28px 80px rgba(0, 0, 0, 0.32),
                0 0 36px rgba(174, 238, 255, 0.24),
                0 0 70px rgba(255, 144, 241, 0.2);
            backdrop-filter: blur(34px) saturate(180%) brightness(1.04);
            -webkit-backdrop-filter: blur(34px) saturate(180%) brightness(1.04);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.015);
            box-shadow:
                inset 0 0 0 1px rgba(255, 255, 255, 0.32),
                inset 0 2px 14px rgba(255, 255, 255, 0.5),
                0 38px 90px rgba(0, 0, 0, 0.38),
                0 0 42px rgba(158, 244, 255, 0.42),
                0 0 88px rgba(255, 158, 244, 0.28);
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 10px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.48);
            pointer-events: none;
            z-index: 3;
            box-shadow:
                inset 0 0 18px rgba(255, 255, 255, 0.34),
                inset 0 0 42px rgba(150, 235, 255, 0.15),
                0 0 20px rgba(255, 255, 255, 0.16);
        }

        .card::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 800px;
            top: -120px;
            left: -100px;
            transform: rotate(28deg);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.32), transparent);
            opacity: 0.34;
            pointer-events: none;
            z-index: 2;
        }

        .glass-shine {
            position: absolute;
            inset: -80px;
            z-index: 1;
            pointer-events: none;
            background:
                conic-gradient(from 210deg at 50% 45%, transparent 0deg, rgba(122, 246, 255, 0.28) 42deg, rgba(255, 178, 247, 0.32) 86deg, rgba(255, 255, 255, 0.42) 118deg, rgba(183, 168, 255, 0.28) 150deg, transparent 205deg),
                linear-gradient(115deg, transparent 24%, rgba(255, 255, 255, 0.32) 42%, transparent 58%);
            mix-blend-mode: screen;
            opacity: 0.7;
            filter: blur(1px);
            transform: rotate(-8deg);
        }

        .card-head,
        .art-box,
        .info-box {
            position: relative;
            z-index: 4;
        }

        .card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 22px;
        }

        .title {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 34px;
            line-height: 1;
            letter-spacing: 0.16em;
            font-weight: 400;
            color: var(--text-main);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.42), 0 4px 18px rgba(0, 0, 0, 0.35);
        }

        .subtitle {
            margin-top: 10px;
            font-size: 14px;
            color: var(--text-sub);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.34);
        }

        .number {
            flex: 0 0 auto;
            padding: 9px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.46);
            box-shadow: inset 0 1px 8px rgba(255, 255, 255, 0.36), 0 10px 22px rgba(0, 0, 0, 0.15);
            font-size: 18px;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .art-box {
            height: 288px;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.42);
            background:
                radial-gradient(circle at 32% 28%, rgba(255, 255, 255, 0.82), transparent 10%),
                radial-gradient(circle at 68% 36%, rgba(116, 247, 255, 0.46), transparent 26%),
                radial-gradient(circle at 34% 76%, rgba(255, 171, 241, 0.42), transparent 28%),
                linear-gradient(135deg, rgba(203, 236, 255, 0.34), rgba(205, 185, 255, 0.28), rgba(255, 226, 247, 0.26));
            box-shadow: inset 0 1px 18px rgba(255, 255, 255, 0.28), 0 16px 28px rgba(0, 0, 0, 0.1);
        }

        .glass-art {
            position: absolute;
            inset: 0;
        }

        .glass-art::before,
        .glass-art::after {
            content: '';
            position: absolute;
            border: 2px solid rgba(255, 255, 255, 0.42);
            background:
                radial-gradient(circle at 28% 24%, rgba(255, 255, 255, 0.7), transparent 18%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.18), rgba(152, 232, 255, 0.14), rgba(255, 177, 242, 0.15));
            box-shadow:
                inset 8px 12px 28px rgba(255, 255, 255, 0.34),
                inset -18px -22px 42px rgba(113, 194, 255, 0.16),
                0 14px 36px rgba(0, 0, 0, 0.12),
                0 0 24px rgba(139, 241, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .shape-orb::before {
            width: 170px;
            height: 170px;
            right: 36px;
            top: 34px;
            border-radius: 999px;
        }

        .shape-orb::after {
            width: 320px;
            height: 70px;
            left: -32px;
            bottom: 64px;
            border-radius: 999px;
            transform: rotate(-18deg);
        }

        .shape-ribbon::before {
            width: 330px;
            height: 72px;
            left: -46px;
            top: 90px;
            border-radius: 999px;
            transform: rotate(26deg);
        }

        .shape-ribbon::after {
            width: 280px;
            height: 68px;
            right: -36px;
            bottom: 56px;
            border-radius: 999px;
            transform: rotate(-25deg);
        }

        .shape-crystal::before {
            width: 160px;
            height: 160px;
            left: 50%;
            top: 48%;
            transform: translate(-50%, -50%) rotate(45deg);
            border-radius: 32px;
        }

        .shape-crystal::after {
            width: 220px;
            height: 60px;
            left: 35px;
            bottom: 44px;
            border-radius: 999px;
            transform: rotate(10deg);
        }

        .shape-diamond::before {
            width: 185px;
            height: 185px;
            right: 54px;
            top: 48px;
            transform: rotate(45deg);
            border-radius: 42px;
        }

        .shape-diamond::after {
            width: 280px;
            height: 78px;
            left: -46px;
            bottom: 54px;
            border-radius: 999px;
            transform: rotate(-31deg);
        }

        .shape-star::before {
            width: 178px;
            height: 178px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) rotate(18deg);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 56%, 79% 91%, 50% 70%, 21% 91%, 32% 56%, 2% 35%, 39% 35%);
        }

        .shape-star::after {
            width: 270px;
            height: 74px;
            right: -58px;
            bottom: 50px;
            border-radius: 999px;
            transform: rotate(24deg);
        }

        .shape-bubble::before {
            width: 150px;
            height: 150px;
            left: 40px;
            top: 52px;
            border-radius: 999px;
        }

        .shape-bubble::after {
            width: 118px;
            height: 118px;
            right: 50px;
            bottom: 48px;
            border-radius: 999px;
        }

        .sparkle {
            position: absolute;
            z-index: 3;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 0 16px rgba(255, 255, 255, 0.8);
            font-size: 30px;
        }

        .sparkle.one { top: 22px; right: 28px; }
        .sparkle.two { left: 26px; bottom: 30px; font-size: 22px; }

        .info-box {
            margin-top: 18px;
            padding: 24px;
            min-height: 190px;
            border-radius: 28px;
            background:
                linear-gradient(145deg, rgba(28, 9, 48, 0.34), rgba(255, 255, 255, 0.08)),
                rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.36);
            box-shadow: inset 0 1px 14px rgba(255, 255, 255, 0.18), 0 12px 24px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px) saturate(145%);
            -webkit-backdrop-filter: blur(20px) saturate(145%);
        }

        .info-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 15px;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.85);
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.36);
        }

        .type {
            font-weight: 800;
            color: rgba(255, 255, 255, 0.96);
        }

        .desc {
            margin-top: 20px;
            min-height: 52px;
            color: rgba(255, 255, 255, 0.76);
            line-height: 1.65;
            font-size: 15px;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.38);
        }

        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.28);
        }

        .stat-label {
            display: block;
            margin-bottom: 11px;
            font-size: 12px;
            letter-spacing: 0.12em;
            color: rgba(255, 255, 255, 0.72);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .dot {
            display: inline-block;
            width: 23px;
            height: 23px;
            margin-right: 6px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.36);
        }

        .dot.on {
            background: radial-gradient(circle at 32% 28%, #ffffff, #9af5ff 35%, #d7baff 65%, #ffd1f5);
            box-shadow: 0 0 14px rgba(160, 241, 255, 0.74);
        }

        .star {
            display: inline-block;
            margin-right: 4px;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.3);
        }

        .star.on {
            color: rgba(255, 255, 255, 0.96);
            text-shadow: 0 0 14px rgba(255, 255, 255, 0.86);
        }

        @media (max-width: 1100px) {
            .card-grid {
                grid-template-columns: repeat(2, minmax(260px, 1fr));
            }
        }

        @media (max-width: 680px) {
            .wrap {
                padding: 46px 16px 64px;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }

            .card {
                min-height: 570px;
                padding: 22px;
            }

            .art-box {
                height: 260px;
            }

            .title {
                font-size: 29px;
            }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="hero">
            <div class="eyebrow">✦ GLASS JEUMORISM COLLECTION ✦</div>
            <h1>LUMINA</h1>
            <p>오로라 거울빛과 투명한 유리알 질감을 담은 카드 컬렉션</p>
        </section>

        <section class="card-grid">
            <?php foreach ($cards as $card): ?>
                <article class="card">
                    <div class="glass-shine"></div>

                    <div class="card-head">
                        <div>
                            <div class="title"><?= htmlspecialchars($card['title']) ?></div>
                            <div class="subtitle"><?= htmlspecialchars($card['subtitle']) ?></div>
                        </div>
                        <div class="number"><?= htmlspecialchars($card['no']) ?></div>
                    </div>

                    <div class="art-box">
                        <div class="glass-art shape-<?= htmlspecialchars($card['shape']) ?>"></div>
                        <div class="sparkle one">✧</div>
                        <div class="sparkle two">✦</div>
                    </div>

                    <div class="info-box">
                        <div class="info-top">
                            <span class="type">✦ <?= htmlspecialchars($card['type']) ?></span>
                            <span><?= htmlspecialchars($card['attribute']) ?></span>
                        </div>

                        <p class="desc"><?= htmlspecialchars($card['desc']) ?></p>

                        <div class="stats">
                            <div>
                                <span class="stat-label">ENERGY</span>
                                <?= dots($card['energy']) ?>
                            </div>
                            <div>
                                <span class="stat-label">HARMONY</span>
                                <?= stars($card['harmony']) ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
