<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lesson Player</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f3f5ff;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
    }

    .lesson-wrapper {
        width: 420px;
        margin-top: 40px;
    }

    .lesson-card {
        background: #fff;
        padding: 20px;
        border-radius: 18px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* .block {
        margin-bottom: 16px;
        padding: 14px;
        background: #f7f8ff;
        border-radius: 12px;
        border: 2px solid #e1e4ff;
    }*/

    .block img {
        max-width: 100%;
        border-radius: 10px;
    }

    .question {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .choice-btn {
        display: block;
        width: 100%;
        margin-bottom: 6px;
        padding: 10px;
        background: white;
        border: 2px solid #dfe3ff;
        border-radius: 10px;
    }

    .nav-buttons {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
    }

    button {
        padding: 12px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }

    #prevBtn { background: #cdd3ff; }
    #nextBtn { background: #7d90ff; color: white; }
</style>
</head>

<body>

<div class="lesson-wrapper">
    <div id="cardContainer" class="lesson-card">
        <!-- Dynamic content here -->
    </div>

    <div class="nav-buttons">
        <button id="prevBtn">← Prev</button>
        <button id="nextBtn">Next →</button>
    </div>
</div>

<script>
// ======================================================
// Dummy data (simulasi fetch dari server)
// ======================================================

const cards = @json($cards);
let currentCardIndex = 0;

// ======================================================
// GENERATE BLOCK ELEMENTS (MODULAR)
// ======================================================
function createBlockElement(block) {
    const blockEl = document.createElement("div");
    blockEl.className = "block";

    switch (block.type) {

        // ========== TEXT ==========
        case "text":
            blockEl.innerHTML = `<p>${block.data.content}</p>`;
            break;

        // ========== IMAGE ==========
        case "image":
            blockEl.innerHTML = `
                <img src="${block.data.url}" alt="${block.data.alt}">
            `;
            break;

        // ========== GIF ==========
        case "gif":
            blockEl.innerHTML = `
                <img src="${block.data.url}" alt="${block.data.alt}">
            `;
            break;

        // ========== VIDEO ==========
        case "video":
            blockEl.innerHTML = `
                <video controls width="100%" preload="auto" style="border-radius: 12px">
                    <source src="${block.data.url}" type="video/mp4">
                    Browser tidak mendukung video.
                </video>
            `;

            if (block.data.duration > 30) {
                console.warn("⚠ Video lebih dari 30 detik:", block.data.url);
            }
            break;

        // ========== CODE (Syntax Highlight) ==========
        case "code":
            blockEl.innerHTML = `
                <pre class="code-block"><code>${escapeHtml(block.data.code)}</code></pre>
            `;
            break;

        // ========== QUIZ ==========
        case "quiz":
            let choicesHtml = "";

            for (const [key, val] of Object.entries(block.data.choices)) {
                choicesHtml += `
                    <button class="choice-btn" 
                        onclick="checkAnswer('${key}', '${block.data.answer}', this)">
                        <b>${key}.</b> ${val}
                    </button>
                `;
            }

            blockEl.innerHTML = `
                <div class="question">${block.data.question}</div>
                ${choicesHtml}
            `;
            break;

        default:
            blockEl.innerHTML = `<p style="color:red">Unknown block type: ${block.type}</p>`;
    }

    return blockEl;
}

// ======================================================
// ESCAPE HTML (untuk CODE block)
// ======================================================
function escapeHtml(str) {
    return str.replace(/[&<>"]/g, function(tag) {
        const chars = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;"
        };
        return chars[tag] || tag;
    });
}

// ======================================================
// RENDER CARD
// ======================================================
function renderCard() {
    const card = cards[currentCardIndex];
    const container = document.getElementById("cardContainer");

    container.innerHTML = ""; // clear old

    card.blocks.forEach(block => {
        const element = createBlockElement(block);
        container.appendChild(element);
    });
}

// ======================================================
// QUIZ CHECK
// ======================================================
function checkAnswer(chosen, correct, element) {
    if (chosen === correct) {
        element.style.background = "#c8f7c5"; // green
    } else {
        element.style.background = "#f7c5c5"; // red
    }
}

// ======================================================
// NAVIGATION BUTTONS
// ======================================================
document.getElementById("nextBtn").onclick = () => {
    if (currentCardIndex < cards.length - 1) {
        currentCardIndex++;
        renderCard();
    }
};

document.getElementById("prevBtn").onclick = () => {
    if (currentCardIndex > 0) {
        currentCardIndex--;
        renderCard();
    }
};
// ======================================================
// INITIAL LOAD
// ======================================================
renderCard();

</script>

</body>
</html>
