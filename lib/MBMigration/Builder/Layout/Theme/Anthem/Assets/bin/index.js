const esbuild = require("esbuild");
const Minimist = require("minimist");

const argv_ = Minimist(process.argv.slice(2));
const IS_PRODUCTION = Boolean(argv_.production);
const minify = Boolean(argv_.minify);
const WATCH = Boolean(argv_.watch);

const entryPoints = ["Menu", "Text", "StyleExtractor", "Globals", "GlobalMenu"];

const baseConfig = {
  bundle: true,
  loader: { ".ts": "ts" },
  minify: minify,
  sourcemap: IS_PRODUCTION ? false : "inline",
  outdir: "dist",
  outbase: "src",
  entryNames: "[dir]",
  format: "iife",
  define: {
    TARGET: JSON.stringify("browser")
  }
};

entryPoints.forEach((entry) => {
  const config = {
    ...baseConfig,
    globalName: "output",
    entryPoints: [`src/${entry}/index.ts`]
  };

  esbuild
    .build(config)
    .then(async () => {
      if (WATCH) {
        console.log("SDK Client: ⚾ Watching for changes...");
        let ctx = await esbuild.context(config);

        await ctx.watch();
      } else {
        console.log("SDK Client: ⚡ Done");
      }
    })
    .catch(() => process.exit(1));
});
