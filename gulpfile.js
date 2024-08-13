const { execSync } = require('child_process');
const { series, src, dest } = require('gulp'),
    fs = require('fs'),
    path = require('path'),
    rename = require('gulp-rename'),
    sourcemaps = require('gulp-sourcemaps'),
    clean = require('gulp-clean'),
    zip = require('gulp-zip');


let pluginName = 'ajax'
let versionNumber = ''
try {
    const data = fs.readFileSync('ajax.php', 'utf8');
    versionNumber = data.match(/Version:\s*([\d.]+)/i)?.[1] || '';
} catch (err) { }

const zipName = `${pluginName}-${versionNumber}.zip`;
const buildDest = './build';

const buildFiles = [
    './**/*',
    '!./build/**',
    '!./node_modules/**',
    '!./test/**',
    '!./.docz/**',
    '!./**/*.zip',
    '!.github',
    '!./readme.md',
    '!.DS_Store',
    '!./**/.DS_Store',
    '!./LICENSE.txt',
    '!./*.json',
    '!yarn-error.log',
    '!bin/**',
    '!tests/**',
    '!.phpunit.result.cache',
    '!.travis.yml',
    '!phpunit.xml.dist',
    '!phpunit.xml',
    '!./asset-manifest.json',
    '!./assets/**/*.less',
    '!./assets/**/*.scss',
    '!./assets/**/*.map',
    '!./vendor/pest-plugins.json',
    '!./webpack.config.js',


    '!./tmp/**',
    '!./gulpfile.js',
    '!./phpcs.xml.dist',
    '!./composer.lock',
    '!./.env',
    '!./assets/admin/src/**'
];

const onError = err => console.log
const cleanSrcConfig = { read: false, allowEmpty: true }

const cleanZip = cb => src('./*.zip', cleanSrcConfig).pipe(clean())
const cleanBuild = cb => src(buildDest, cleanSrcConfig).pipe(clean())

const makePod = (cb) => {
    let excludes = `node_modules,vendor,.github,build,tests,*.json,*.txt,*.md,gulpfile.js,*.map`,
        includes = `assets/admin/js/*,src/**/*.php`,
        cmd = `wp i18n make-pot . assets/languages/${pluginName}.pot --exclude=${excludes} --include=${includes}`
    try {
        execSync(cmd)
        cb()
    } catch (error) {
        onError(error)
    }
}

const build = cb => src(buildFiles).pipe(dest(`${buildDest}/${pluginName}`))
const buildZip = cb => src(`${buildDest}/**/*`).pipe(zip(zipName)).pipe(dest('./'))


exports.build = series(cleanZip, cleanBuild, makePod, build, buildZip)