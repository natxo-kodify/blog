# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.define "devel" do |devel|
    devel.vm.box = "devel/box"
    devel.vm.network "private_network", ip: "192.168.33.10"
    devel.vm.hostname = "devel"

    #devel.vm.synced_folder ".", "/var/www/public", :mount_options => ["dmode=777", "fmode=666"]
    # Optional NFS. Make sure to remove other synced_folder line too
    devel.vm.synced_folder ".", "/var/www/public", :nfs => { :mount_options => ["dmode=777","fmode=666"] }
    devel.ssh.username = 'root'
  end
end
