import globals from 'globals'
import js from '@eslint/js'
import ts from 'typescript-eslint'
import vue from 'eslint-plugin-vue'
import parser from 'vue-eslint-parser'

export default [
  js.configs.recommended,

  ...ts.configs.recommended,

  ...vue.configs['flat/essential'],

  {
    ignores: [
      '**/node_modules',
      'public/build',
      '**/vendor'
    ],
  },

  {
    files: [
      'resources/js/**/*.js',
      'resources/js/**/*.ts',
      'resources/js/**/*.vue',
    ],

    plugins: {
      '@typescript-eslint': ts.plugin
    },

    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
      parser: parser,
      parserOptions: {
        parser: {
          'ts': ts.parser,
        },
        extraFileExtensions: ['.vue'],
      }
    },

    rules: {
      'no-unused-vars': 'off',
      'no-undef': 'off',
      'no-useless-constructor': 'error',
      'no-duplicate-imports': 'error',
      'no-var': 'error',
      'prefer-const': 'error',
      'prefer-rest-params': 'error',
      'prefer-spread': 'error',

      '@typescript-eslint/no-unused-vars': 'warn',
      '@typescript-eslint/explicit-function-return-type': 'off',

      'vue/multi-word-component-names': 'off',
    }
  },
]
