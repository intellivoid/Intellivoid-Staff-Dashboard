import sys
import json

class IV_FeatureBuilder(object):
	def __init__(self):
		self.__version__ = "1.0"
		self.__author__ = "Zi Xing"
		self.__organization__ = "Intellivoid"
		self.features = []

	def intro(self):
		intro_message = "Feature Builder Script v1.0\n" \
						"Copyright © 2017-2019 Intellivoid. All rights reserved.\n\n" \
						"This tool is intended to generate valid a JSON structure for\n" \
						"Subscription Features, these structures represents the\n" \
						"features that a subscription plan may offer. This is simply\n" \
						"a tool and it cannot and should not interact with Intellivoid's\n" \
						"servers or backend.\n" \
						"==================================================================\n\n"
		print(intro_message)

	def builder(self):
		menu_message = "Choose one of the available options\n\n" \
					" 0) Show help\n" \
					" 1) Create Feature\n" \
					" 2) Remove Feature\n" \
					" 3) Clear all Features\n" \
					" 4) List current features\n" \
					" 5) Export\n" \
					" 6) (CTRL+C) Exit Program\n"
		print(menu_message)

		while True:
			user_input = input("Choice: ")

			try:
				if user_input == "0":
					print(menu_message)
					continue

				if user_input == "1":
					self.add_feature()
					continue

				if user_input == "2":
					self.remove_feature()
					continue

				if user_input == "3":
					self.features = []
					print("Features cleared from memory")
					continue

				if user_input == "4":
					self.list_current_features()
					continue

				if user_input == "5":
					print("\n{0}\n".format(json.dumps(self.features)))
					continue

				if user_input == "6":
					sys.exit(0)

				print("Invalid Choice")
			except Exception as ex:
				error_message = "Program Error\n\n" \
					"Type: {0}\n" \
					"Arguments: {1}\n" \
					"Exception: {2}\n".format(type(ex), ex.args, ex)
				print(error_message)

	def list_current_features(self):
		if len(self.features) == 0:
			print("There are no features to display")
			return
		
		for index in range(len(self.features)):
			print(" [{0}]	'{1}' => {2}".format(
				str(index),
				str(self.features[index]['name']),
				str(self.features[index]['value'])
			))

	def add_feature(self):
		feature_name = input("Name: ")
		feature_value = None

		while True:
			value_type = input("Value Type (str/bool/int): ").lower()

			if value_type == "str":
				feature_value = str(input("Value: "))
				break
				
			if value_type == "bool":
				feature_value = bool(input("Value: "))
				break

			if value_type == "int":
				feature_value = int(input("Value: "))
				break

			print("Unsupported Type")

		self.features.append({
			"name": feature_name,
			"value": feature_value
		})

	def remove_feature(self):
		index = int(input("Feature index: "))
		del self.features[index]


if __name__ == "__main__":
	try:
		MainProgram = IV_FeatureBuilder()
		MainProgram.intro()
		MainProgram.builder()
	except KeyboardInterrupt:
		sys.exit(0)