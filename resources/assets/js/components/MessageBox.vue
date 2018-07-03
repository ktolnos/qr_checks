<template>
    <div class="message-box dialog-mask" @click="$close">
        <div class="dialog-content">
            <div class="dialog-body">
                <p>{{ content }}</p>
            </div>
            <footer>
                <button type="text" size="mini" @click="$close">OK</button>
            </footer>
        </div>
    </div>
</template>