# To learn more about how to use Nix to configure your environment
# see: https://developers.google.com/idx/guides/customize-idx-env
{ pkgs, ... }: {
  # Which nixpkgs channel to use.
  channel = "stable-23.11"; # or "unstable"

  # Use https://search.nixos.org/packages to find packages
  packages = [
    pkgs.php83
    pkgs.php83Packages.composer
    pkgs.php83Extensions.redis

    pkgs.php83Extensions.gd
    pkgs.php83Extensions.zip
    pkgs.php83Extensions.pdo
    pkgs.php83Extensions.curl
    pkgs.php83Extensions.pgsql
    pkgs.php83Extensions.iconv
    pkgs.php83Extensions.xdebug
    pkgs.php83Extensions.session
    pkgs.php83Extensions.opcache
    pkgs.php83Extensions.readline
    pkgs.php83Extensions.mbstring
    pkgs.php83Extensions.pdo_pgsql
    pkgs.htop

    pkgs.postgresql_16
    pkgs.postgresql16Packages.postgis
    pkgs.acl.bin
    pkgs.crudini
    pkgs.openssh
  ];

  services.docker.enable = true;
#  services.redis.enable = true;
 # services.redis.port = 6379;
  # services.postgres.enable = true;
  # services.postgres.extensions = ["postgis"];

  #services.postgres.enable = true;
  #services.postgres.package = pkgs.postgresql_16;
  #services.postgres.extensions = ["postgis" "pgvector"];

  # Sets environment variables in the workspace
  env = {};
  idx = {
    # Search for the extensions you want on https://open-vsx.org/ and use "publisher.id"
    extensions = [
      # "vscodevim.vim"
    ];

    # Enable previews
    previews = {
      enable = true;
      previews = {
        # web = {
        #   # Example: run "npm run dev" with PORT set to IDX's defined port for previews,
        #   # and show it in IDX's web preview panel
        #   command = ["npm" "run" "dev"];
        #   manager = "web";
        #   env = {
        #     # Environment variables to set for your server
        #     PORT = "$PORT";
        #   };
        # };
      };
    };

    # Workspace lifecycle hooks
    workspace = {
      # Runs when a workspace is first created
      onCreate = {
        setup = "sh ./.idx/setup.sh";
      };
      # Runs when the workspace is (re)started
      onStart = {
        # Example: start a background task to watch and re-build backend code
        start-web = "[ -f ./vendor/bin/sail ] && ./vendor/bin/sail up";
        chmod = "chmod -fR 777 .";
      };
    };
  };
}
