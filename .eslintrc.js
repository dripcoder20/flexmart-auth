module.exports = {
    env: {
        browser: true,
        node: true,
        es6: true
    },
    extends: ['plugin:vue/essential', 'plugin:prettier/recommended'],
    globals: {
        Atomics: 'readonly',
        SharedArrayBuffer: 'readonly'
    },
    parserOptions: {
        ecmaVersion: 11,
        sourceType: 'module'
    },
    plugins: ['prettier', 'vue'],
    rules: {
        quotes: ['error', 'single'],
        'comma-dangle': ['error', 'never']
    }
}
