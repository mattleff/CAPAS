Vagrant.configure("2") do |config|
	config.vm.box = "ubuntu/trusty32"
	config.vm.provision :shell, path: "bootstrap.sh"
	config.vm.network :forwarded_port, host: 22727, guest: 80
end